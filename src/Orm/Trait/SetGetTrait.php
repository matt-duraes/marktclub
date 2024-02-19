<?php

namespace ORM\Trait;

use Erro\Erro;
use ORM\Entity;
use Erro\Excecao;
use Modules\Botao;
use Modules\Senha;
use Modules\DataHora;
use ReflectionProperty;
use Order\OrderInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait SetGetTrait
{
    /**
     * @param  null|string $propriedade Propriedade que será buscada
     * @param  null|array  $lista       Lista com as propriedades que deseja listar
     * @return mixed
     */
    public function get(?string $propriedade = null, ?array $lista = null)
    {
        if ($this->ormEntityDeletada) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Esse Entity foi destruido.');
        }
        $this->ormVerificarSeEntityExiste();
        if (is_null($lista)) {
            return $this->ormPegarGet($propriedade);
        }
        $retorno = [];
        foreach ($lista as $ind => $val) {
            $retorno[] = $this->ormPegarGet($ind, $val);
        }
        return $retorno;
    }

    private function ormPegarGet(string $propriedade)
    {
        $metodo = 'get' . str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $propriedade), 'UTF-8')));
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        } elseif (
            (property_exists($this, $propriedade) && !is_null($this->$propriedade)) &&
            in_array($propriedade, $this->ormPropriedadePublica)
        ) {
            return $this->$propriedade;
        }
        throw new Erro(mensagem: 'A propriedade ' . $propriedade . ' não existe.');
    }

    /**
     * @param string     $propriedade Propriedade que será setada
     * @param mixed      $valor       Valor da propriedade que será setada
     * @param null|array $lista       Array com uma lista para setar em massa
     */
    public function set(string $propriedade = '', $valor = '', ?array $lista = null): void
    {
        if (!($this instanceof Entity)) {
            $this->setarMetodoORM($propriedade, $valor, $lista);
            return;
        }
        if ($this->ormEntityDeletada) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Esse Entity foi destruido.');
        }
        $this->ormVerificarSeEntityExiste();
        if (is_null($lista)) {
            $this->ormSetarSet($propriedade, $valor, true);
            return;
        }
        if (!$lista) {
            return;
        }
        foreach ($lista as $ind => $val) {
            $this->ormSetarSet($ind, $val, true);
        }
        $this->ormValidarEmpresa();
    }

    private function ormValidarEmpresa()
    {
        if (method_exists($this, 'validarEmpresa') && !$this->empresaValidada) {
            $this->validarEmpresa();
        }
    }

    private function setarMetodoORM(string $propriedade = '', $valor = '', ?array $lista = null)
    {
        $propriedadeLista = get_class_vars(get_class($this));
        if (!is_array($propriedadeLista) || !$propriedadeLista) {
            return;
        }

        $propriedadeFinal = [];
        foreach (array_keys($propriedadeLista) as $ind) {
            if (str_starts_with($ind, 'orm')) {
                continue;
            }
            $propriedadeFinal[] = $ind;
        }
        $this->ormListaSet = $propriedadeFinal;

        if (is_null($propriedade)) {
            $this->ormSetarSet($propriedade, $valor);
            return;
        } elseif (!is_array($lista)) {
            return;
        }
        foreach ($lista as $ind => $val) {
            $this->ormSetarSet($ind, $val);
        }
        $this->ormValidarEmpresa();
    }

    private function ormVerificarSeEntityExiste()
    {
        if ($this->ormEntityDeletada) {
            throw new Erro(mensagem: 'Essa entidade foi destruida e você não tem mais acesso a ela.');
        }
    }

    private function ormSetarSet(string $propriedade, $valor): void
    {
        if (in_array($propriedade, ['id', 'uuid'])) {
            return;
        }

        if (is_array($valor) && array_key_exists('name', $valor) && array_key_exists('postname', $valor)) {
            $valor = new UploadedFile(
                path: $valor['name'],
                originalName: $valor['postname'],
                mimeType: $valor['mime'] ?? ''
            );
        }

        $metodo = 'set' . str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $propriedade), 'UTF-8')));
        if (method_exists($this, $metodo)) {
            $this->ormSetReal[$propriedade] = $valor;
            $this->$metodo($valor);
            return;
        }

        $valorValidacao = $valor;
        if ($valorValidacao instanceof ModuleInterface) {
            $valorValidacao = $this->ormPegarValorModule($valor);
        } elseif ($valorValidacao instanceof StatusInterface) {
            $valorValidacao = $valorValidacao->numero();
        }
        $existe = in_array($propriedade, $this->ormListaSet);
        if (is_null($valorValidacao) && $existe) {
            return;
        } elseif ($existe) {
            $valor = $this->ormConverterValorSeForUmModule($propriedade, $valor, 1);
            $valor = $this->ormConverterValorSeForUmStatus($propriedade, $valor);
            $valor = $this->ormConverterValorSeForUmaOrdem($propriedade, $valor);
            $this->ormSetReal[$propriedade] = $valor;
            if ($valor instanceof Senha && $valor->vazio()) {
                return;
            }
            $this->$propriedade = $valor;
            $this->ormPropriedadeSetada[] = strSlug($propriedade);
            return;
        }
        throw new Erro(mensagem: 'A propriedade ' . $propriedade . ' não existe ou você não tem acesso a ela.');
    }

    protected function ormPegarValorModule(ModuleInterface $valor)
    {
        if ($valor instanceof ModuleInterface) {
            return $valor->banco();
        }
        return null;
    }

    private function ormConverterValorSeForUmModule(string $indice, $valor, bool $teste = false)
    {
        if ($valor instanceof ModuleInterface) {
            return $valor;
        }

        if (in_array($indice, ['data_criacao', 'data_atualizacao'])) {
            return new DataHora($valor);
        }

        try {
            $nome = (new ReflectionProperty($this, $indice))->getType()->getName();
        } catch (\Throwable) {
            $nome = '';
        }

        if (empty($nome)) {
            return $valor;
        }

        $classNome = str_starts_with($nome, 'Modules\\') && class_exists($nome) ? new $nome(null) : $nome;

        $valor = is_null($valor) ? '' : $valor;
        if ($nome == 'Modules\Senha' && $this->propriedadeExiste($indice)) {
            $this->$indice->mudarSenha($valor);
            $valor = $this->$indice;
        } elseif ($nome == 'Modules\Senha') {
            $valor = new Senha(senha: $valor);
        } elseif ($nome == 'Modules\Botao') {
            $valor = new Botao(valor: in_array($valor, [1, 'sim']) ? 'sim' : 'nao');
        } elseif ($classNome instanceof ModuleInterface) {
            $valor = new $nome($valor);
        } elseif ($nome == 'array') {
            $valor = jsonDecode($valor, true, true);
        } elseif ($nome == 'int') {
            $valor = filter_var($valor, FILTER_VALIDATE_INT) ? $valor : 0;
        } elseif ($nome == 'float') {
            $valor = filter_var($valor, FILTER_VALIDATE_FLOAT) ? $valor : 0;
        } elseif ($nome == 'bool') {
            $valor = $valor == 1 ? true : false;
        }
        return $valor;
    }

    private function ormConverterValorSeForUmStatus(string $indice, $valor)
    {
        if ($valor instanceof StatusInterface) {
            return $valor;
        }

        try {
            $nome = (new ReflectionProperty($this, $indice))->getType()->getName();
            $valorTemp = new $nome($valor);
        } catch (\Throwable) {
            $valorTemp = '';
        }

        if ($valorTemp instanceof StatusInterface) {
            return $valorTemp;
        }
        return $valor;
    }

    private function ormConverterValorSeForUmaOrdem(string $indice, $valor)
    {
        if ($valor instanceof OrderInterface) {
            return $valor;
        }

        try {
            $nome = (new ReflectionProperty($this, $indice))->getType()->getName();
            $valorTemp = new $nome($valor);
        } catch (\Throwable) {
            $valorTemp = '';
        }

        if ($valorTemp instanceof OrderInterface) {
            return $valorTemp;
        }
        return $valor;
    }

    /**
     * Alias para propriedadeExiste
     *
     * @param  string $propriedade Propriedade que deseja validar
     * @param  bool   $vazio       Se true, ele aceita que a propriedade seja vazia
     * @return bool
     */
    public function pExiste($propriedade, bool $vazio = true): bool
    {
        return $this->propriedadeExiste($propriedade, $vazio);
    }

    /**
     * Verifica se a propriedade existe
     *
     * @param  string $propriedade Propriedade que deseja validar
     * @param  bool   $vazio       Se true, ele aceita que a propriedade seja vazia
     * @return bool
     */
    public function propriedadeExiste(string $propriedade, bool $vazio = true): bool
    {
        if (!property_exists($this, $propriedade)) {
            return false;
        }
        $propriedade = new \ReflectionProperty($this, $propriedade);
        if ($vazio) {
            return $propriedade->isInitialized($this);
        }
        return $propriedade->isInitialized($this) && !empty($this->$propriedade);
    }
}
