<?php

namespace ORM;

use ORM\ORM;
use Erro\Erro;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Cnpj;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Decimal;
use Modules\DataHora;
use Modules\Dinheiro;
use Modules\Telefone;
use ReflectionObject;
use ReflectionProperty;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use ORM\Trait\MudouTrait;
use Modules\EnderecoEstado;
use ORM\Buscar\BuscarTrait;
use ORM\Salvar\SalvarTrait;
use Status\StatusInterface;
use Modules\ModuleInterface;
use ORM\Join\RelacionarTrait;
use ORM\Deletar\DestruirTrait;

abstract class Entity extends ORM
{
    use SalvarTrait;
    use RelacionarTrait;
    use BuscarTrait;
    use DestruirTrait;
    use MudouTrait;

    public string $id = '';

    private array $_relacionado = [];

    protected array $_buscar = ['id'];
    protected array $_salvar = [];
    protected array $_insert = [];
    protected array $_update = [];
    protected ?array $_diff = null;

    protected string $_validarSalvar = '';
    protected string $_validarInsert = '';
    protected string $_validarUpdate = '';

    protected array $_deletarArquivo = [];

    protected array $_wherePadrao = [];
    protected array $_set = [];
    protected array $_get = [];
    protected array $_entityRetorno = [];
    private bool $_entityDeletada = false;

    private ?int $_entityId = 0;
    private string $_entityAcao = '';
    private string $_entityUuid = '';
    private array $_propriedadePublica = [];
    private array $_propriedadePrivada = [];
    private array $_listaSet = [];
    protected array $_setReal = [];
    private array $_listaAliasReal = [];

    protected bool $entityExiste = false;

    protected $_campoBanco = [];

    /**
     * @param Array         $option         Option aceitos pelo PDO
     * @param Array         $conn           Option para a conexao podendo ser:
     *                                          host, banco, usuario e senha.
     *                                          Caso não informa, será usado o ENV
     */
    public function __construct(array $option = [], array $conn = [])
    {
        parent::__construct($option, $conn);
        $this->_ormTipo = 'entity';
        $this->ormMontarPropriedadePublica();
        $this->ormPegarListaParaSet();
        $this->ormPegarListaDeAliasEReal();
        $this->_campoBanco = $this->ormPegarColunaBanco();
    }

    /**
     * Pega o que foi alterado depois de um insert, update ou delete
     */
    public function diff()
    {
        if (is_null($this->_diff)) {
            throw new Excecao('Erro!', 'Você só pode usar o diff apois um insert, update ou delete', 403);
        }
        return $this->_diff;
    }

    public function propriedadeExiste($propriedade)
    {
        if (!property_exists($this, $propriedade)) {
            return false;
        }
        $propriedade = new \ReflectionProperty($this, $propriedade);
        return $propriedade->isInitialized($this);
    }

    /**
     * @param Null|String       $propriedade        Propriedade que será buscada
     * @param Null|Array        $lista              Lista com as propriedades que deseja listar
     * @return Mixed
     */
    public function get(?string $propriedade = null, ?array $lista = null)
    {
        if ($this->_entityDeletada) {
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

    /**
     * @param String        $propriedade        Propriedade que será setada
     * @param Mixed         $valor              Valor da propriedade que será setada
     * @param Null|Array    $lista              Array com uma lista para setar em massa
     */
    public function set(string $propriedade = '', $valor = '', ?array $lista = null): void
    {
        if ($this->_entityDeletada) {
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
    }

    protected function prop(string $propriedade)
    {
        if ($this->_entityDeletada) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Esse Entity foi destruido.');
        }
        $retorno = $this->_entityRetorno;
        if (array_key_exists($propriedade, $retorno)) {
            return $retorno[$propriedade];
        }
        throw new Erro(mensagem: 'Não existe a prop ' . $propriedade . '.');
    }

    private function ormPegarGet(string $propriedade)
    {
        $metodo = 'get' . str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $propriedade), 'UTF-8')));
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        } elseif (
            (property_exists($this, $propriedade) && !is_null($this->$propriedade)) &&
            in_array($propriedade, $this->_propriedadePublica)
        ) {
            return $this->$propriedade;
        }
        throw new Erro(mensagem: 'A propriedade ' . $propriedade . ' não existe.');
    }

    private function ormSetarSet(string $propriedade, $valor): void
    {
        if (in_array($propriedade, ['id', 'uuid'])) {
            return;
        }
        $metodo = 'set' . str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $propriedade), 'UTF-8')));
        if (method_exists($this, $metodo)) {
            $this->_setReal[$propriedade] = $valor;
            $this->$metodo();
            return;
        }

        $valorValidacao = $valor;
        if ($valorValidacao instanceof ModuleInterface) {
            $valorValidacao = $this->ormPegarValorModule($valor);
        } else if ($valorValidacao instanceof StatusInterface) {
            $valorValidacao = $valorValidacao->numero();
        }
        $existe = in_array($propriedade, $this->_listaSet);
        if (is_null($valorValidacao) && $existe) {
            return;
        } elseif ($existe) {
            $valor = $this->ormConverterValorSeForUmModule($propriedade, $valor);
            $valor = $this->ormConverterValorSeForUmStatus($propriedade, $valor);
            $this->_setReal[$propriedade] = $valor;
            if ($valor instanceof Senha && $valor->vazio()) {
                return;
            }
            $this->$propriedade = $valor;
            return;
        }
        throw new Erro(mensagem: 'A propriedade ' . $propriedade . ' não existe ou você não tem acesso a ela.');
    }

    private function ormPegarValorModule(ModuleInterface $valor)
    {
        if ($valor instanceof Email) {
            return $valor->email();
        } elseif ($valor instanceof Nome) {
            return $valor->nome();
        } elseif ($valor instanceof Genero) {
            return $valor->numero();
        } elseif ($valor instanceof EstadoCivil) {
            return $valor->numero();
        } elseif ($valor instanceof Data) {
            return $valor->date();
        } elseif ($valor instanceof DataHora) {
            return $valor->date();
        } elseif ($valor instanceof Senha) {
            return $valor->senha();
        } elseif ($valor instanceof Telefone) {
            return $valor->numero();
        } elseif ($valor instanceof Cpf) {
            return $valor->numero();
        } elseif ($valor instanceof Botao) {
            return $valor->numero();
        } elseif ($valor instanceof Cnpj) {
            return $valor->numero();
        } elseif ($valor instanceof EnderecoCep) {
            return $valor->numero();
        } elseif ($valor instanceof EnderecoEstado) {
            return $valor->estado();
        } elseif ($valor instanceof Dinheiro) {
            return $valor->decimal();
        } elseif ($valor instanceof Decimal) {
            return $valor->decimal();
        }
        return null;
    }

    private function ormPegarListaDeSalvarInsertUpdate()
    {
        $acao = empty($this->_entityId) ? 'insert' : 'update';
        $lista = [];
        if (property_exists($this, '_salvar') && is_array($this->_salvar) && $this->_salvar) {
            $lista = array_merge($lista, $this->_salvar);
        }
        if ($acao == 'insert' && property_exists($this, '_insert') && is_array($this->_insert) && $this->_insert) {
            $lista = array_merge($lista, $this->_insert);
        } elseif ($acao == 'update' && property_exists($this, '_update') && is_array($this->_update) && $this->_update) {
            $lista = array_merge($lista, $this->_update);
        }
        return $lista;
    }

    private function ormPegarListaParaSet()
    {
        $lista = $this->ormPegarListaDeSalvarInsertUpdate();
        if (!$lista) {
            return;
        }
        $retorno = [];
        foreach ($lista as $ind => $val) {
            $indice = $ind;
            if (is_int($ind)) {
                $indice = $val;
            }
            if (preg_match('/^\-\>/', $val)) {
                $indice = explode('->', $val)[1] ?? '';
            }
            $retorno[] = $indice;
        }
        $this->_listaSet = array_merge($retorno, $this->_propriedadePublica);
    }

    private function ormPegarListaDeAliasEReal()
    {
        $lista = $this->ormPegarListaDeSalvarInsertUpdate();
        if (!$lista) {
            return;
        }
        $retorno = [];
        foreach ($lista as $ind => $val) {
            $indice = $ind;
            $valor = $ind;
            if (is_int($ind)) {
                $indice = $val;
                $valor = $val;
            }
            if (preg_match('/^\-\>/', $val)) {
                $indice = explode('->', $val)[1] ?? '';
            }
            $retorno[$indice] = $valor;
        }
        $this->_listaAliasReal = $retorno;
    }

    private function ormMontarPropriedadePublica(): void
    {
        $reflect = new ReflectionObject($this);
        $propriedade = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        if (!$propriedade) {
            return;
        }
        $lista = [];
        foreach ($propriedade as $r) {
            $lista[] = $r->name;
        }
        $buscar = property_exists($this, '_buscar') && is_array($this->_buscar) && $this->_buscar ? $this->_buscar : [];
        if (!$buscar) {
            $this->_propriedadePublica = $lista;
            return;
        }

        foreach ($buscar as $ind => $val) {
            $indice = is_int($ind) ? $val : $ind;
            if (!str_starts_with($indice, '!')) {
                $lista[] = $indice;
            }
        }
        $this->_propriedadePublica = array_unique($lista);
    }

    private function ormSetarDadoDaEntity(array $dado, string $acao): void
    {
        $lista = $this->ormListaParaMontarEntity();
        if (!array_key_exists('id', $lista) && array_key_exists('id', $dado)) {
            $lista['id'] = 'id';
        }
        if (!array_key_exists('uuid', $lista) && array_key_exists('uuid', $dado)) {
            $lista['uuid'] = 'uuid';
        } else if (!array_key_exists('uuid', $lista) && array_key_exists('cod', $dado)) {
            $lista['uuid'] = 'cod';
        }

        foreach ($lista as $prop => $campo) {
            if (is_array($campo)) {
                $valor = '';
                foreach ($campo as $subCampo) {
                    if (!array_key_exists($subCampo, $dado)) {
                        mensagemErro('Erro!', 'Ocorre um erro ao buscar registro', status: 500, localhost: 'Não existe o campo ' . $subCampo . ' no banco.');
                    }
                    if (!empty($dado[$subCampo])) {
                        $valor = $dado[$subCampo];
                        break;
                    }
                }
            } else {
                if (!array_key_exists($campo, $dado)) {
                    mensagemErro('Erro!', 'Ocorre um erro ao buscar registro', status: 500, localhost: 'Não existe o campo ' . $campo . ' no banco.');
                }
                $valor = is_null($dado[$campo]) ? '' : $dado[$campo];
            }

            $privado = false;
            if (str_starts_with($prop, '!')) {
                $privado = true;
                $prop = substr($prop, 1);
            }

            if ($prop == 'id') {
                $this->_entityId = $valor;
                continue;
            } elseif ($prop == 'uuid') {
                $this->id = $valor;
                $this->_entityUuid = $valor;
                continue;
            }

            if ($this->ormDadoContemUmSet($prop, $valor, $acao)) {
                continue;
            }

            $valor = $this->ormConverterValorSeForUmModule($prop, $valor);
            $valor = $this->ormConverterValorSeForUmStatus($prop, $valor);

            if ($privado) {
                $this->_propriedadePrivada[$prop] = $valor;
                continue;
            }

            $tipoPropriedade = '';
            if (property_exists($this, $prop)) {
                $rp = new ReflectionProperty($this, $prop);
                if (is_object($rp->getType()) && method_exists($rp->getType(), 'getName')) {
                    $tipoPropriedade = $rp->getType()->getName();
                }
            }
            if ($tipoPropriedade == 'array') {
                $this->$prop = jsonDecode($valor, true, true);
                continue;
            } elseif ($tipoPropriedade == 'int') {
                $this->$prop = filter_var($valor, FILTER_VALIDATE_INT) ? $valor : 0;
                continue;
            } elseif ($tipoPropriedade == 'float') {
                $this->$prop = filter_var($valor, FILTER_VALIDATE_FLOAT) ? $valor : 0;
                continue;
            } elseif ($tipoPropriedade == 'bool') {
                $this->$prop = $valor == 1 ? true : false;
                continue;
            }

            $this->$prop = $valor;
        }
    }

    private function ormListaParaMontarEntity(): array
    {
        $lista = $this->_buscar ?? [];
        $listaJoin = $this->_relacionado;
        foreach ($listaJoin as $r) {
            if (!array_key_exists('campo', $r)) {
                continue;
            }
            $campo = $r['campo'];

            $alias = array_key_exists('alias', $r) ? $r['alias'] . '_' : '';
            foreach ($campo as $rCampo) {
                $lista[] = $alias . $rCampo;
            }
        }

        if (!$lista) {
            return [];
        }

        $retorno = [];
        foreach ($lista as $ind => $val) {
            $indice = $val;
            $valor = $ind;
            if (is_int($ind)) {
                $valor = $val;
            }
            if (is_array($indice)) {
                $retorno[$ind] = $indice;
                continue;
            }
            $retorno[$valor] = preg_replace('/^\!/', '', $indice);
        }
        return $retorno;
    }

    private function ormDadoContemUmSet(string $indice, $valor, string $acao): bool
    {
        $indice = substr($indice, 0, 1) == '!' ? substr($indice, 1) : $indice;
        $metodo = str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $indice), 'UTF-8')));
        $metodoBuscar = 'setBuscar' . $metodo;
        $metodoInsert = 'setInsert' . $metodo;
        $metodoUpdate = 'setUpdate' . $metodo;
        $metodoSalvar = 'setSalvar' . $metodo;
        $metodoGeral = 'set' . $metodo;

        if ($acao == 'buscar' && method_exists($this, $metodoBuscar)) {
            $this->$metodoBuscar($valor);
            return true;
        } elseif ($acao == 'insert' && method_exists($this, $metodoInsert)) {
            $this->$metodoInsert($valor);
            return true;
        } elseif ($acao == 'update' && method_exists($this, $metodoUpdate)) {
            $this->$metodoUpdate($valor);
            return true;
        } elseif (in_array($acao, ['insert', 'update']) && method_exists($this, $metodoSalvar)) {
            $this->$metodoSalvar($valor);
            return true;
        }
        if (method_exists($this, $metodoGeral)) {
            $this->$metodoGeral($valor);
            return true;
        }
        return false;
    }

    private function ormConverterValorSeForUmModule(string $indice, $valor)
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

        $valor = is_null($valor) ? '' : $valor;
        if ($nome == 'Modules\Email') {
            $valor = new Email(email: $valor);
        } elseif ($nome == 'Modules\Data') {
            $valor = new Data(data: $valor);
        } elseif ($nome == 'Modules\DataHora') {
            $valor = new DataHora(data: $valor);
        } elseif ($nome == 'Modules\Nome') {
            $valor = new Nome(nome: $valor);
        } elseif ($nome == 'Modules\Senha' && $this->propriedadeExiste($indice)) {
            $this->$indice->mudarSenha($valor);
            $valor = $this->$indice;
        } elseif ($nome == 'Modules\Senha') {
            $valor = new Senha(senha: $valor);
        } elseif ($nome == 'Modules\Telefone') {
            $valor = new Telefone(telefone: $valor);
        } elseif ($nome == 'Modules\Cnpj') {
            $valor = new Cnpj(cnpj: $valor);
        } elseif ($nome == 'Modules\Cpf') {
            $valor = new Cpf(cpf: $valor);
        } elseif ($nome == 'Modules\Botao') {
            $valor = new Botao(valor: in_array($valor, [1, 'sim']) ? 'sim' : 'nao');
        } elseif ($nome == 'Modules\Genero') {
            $valor = new Genero(genero: $valor);
        } elseif ($nome == 'Modules\EstadoCivil') {
            $valor = new EstadoCivil(estadoCivil: $valor);
        } elseif ($nome == 'Modules\EnderecoCep') {
            $valor = new EnderecoCep(cep: $valor);
        } elseif ($nome == 'Modules\EnderecoEstado') {
            $valor = new EnderecoEstado(estado: $valor);
        } elseif ($nome == 'Modules\Dinheiro') {
            $valor = new Dinheiro(dinheiro: $valor);
        } elseif ($nome == 'Modules\Decimal') {
            $valor = new Decimal(decimal: $valor);
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

        if (!empty($valorTemp) && $valorTemp instanceof StatusInterface) {
            return $valorTemp;
        }
        return $valor;
    }

    private function ormMontarArrayIndiceForValor($dado)
    {
        $lista = [];
        foreach ($dado as $ind => $val) {
            if (is_int($ind)) {
                $lista[] = $val;
                continue;
            }
            $lista[] = $ind;
        }
        return $lista;
    }

    private function ormPegarValorPropriedade(
        string $propriedade,
        bool $module = true,
        $classe = null,
        bool $erro = true
    ) {
        $classe = is_null($classe) ? $this : $classe;
        if (!is_object($classe)) {
            return null;
        }
        if (!property_exists($classe, $propriedade) && $erro) {
            throw new Erro(mensagem: 'Não foi encontrado a propriedade ' . $propriedade . '.');
        } elseif (!property_exists($classe, $propriedade)) {
            return null;
        }
        $prop = new ReflectionProperty($classe, $propriedade);
        if (!$prop->isPublic()) {
            $prop->setAccessible(true);
        }
        if (!$prop->isInitialized($classe)) {
            return null;
        }
        $valor = $prop->getValue($classe);
        if ($module && $valor instanceof ModuleInterface) {
            return $this->ormPegarValorModule($valor);
        } else if ($module && $valor instanceof StatusInterface) {
            return $valor->numero();
        }
        return $valor;
    }

    private function ormVerificarSeEntityExiste()
    {
        if ($this->_entityDeletada) {
            throw new Erro(mensagem: 'Essa entidade foi destruida e você não tem mais acesso a ela.');
        }
    }

    private function ormPegarArquivoParaDeletar(array $dado, bool $todos = false): array
    {
        if (!$this->_deletarArquivo) {
            return [];
        }

        $lista = [];
        foreach ($this->_deletarArquivo as $campo => $diretorio) {
            if (!array_key_exists($campo, $dado)) {
                continue;
            }
            $diretorio = preg_replace('/\/$/', '', $diretorio);
            $valorAtual = $this->prop($campo);
            $campoNovo = $dado[$campo];
            if (
                !empty($valorAtual) &&
                file_exists($diretorio . '/' . $valorAtual) &&
                ($todos || $valorAtual != $campoNovo)
            ) {
                $lista[] = $diretorio . '/' . $valorAtual;
            }
        }
        return $lista;
    }
    private function ormDeletarArquivos(array $lista): void
    {
        if (!$lista) {
            return;
        }
        foreach ($lista as $arquivo) {
            unlink($arquivo);
        }
    }
}
