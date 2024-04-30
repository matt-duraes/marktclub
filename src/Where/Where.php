<?php

namespace Where;

use Closure;
use ORM\ORM;
use ORM\Entity;
use Modules\Botao;
use Status\StatusInterface;
use Modules\ModuleInterface;

final class Where implements WhereInterface
{
    private bool $temp = false;
    private array $whereTemp = [];
    private bool $grupo = false;
    private array $whereGrupo = [];

    /**
     * Inicializa a classe de Where
     *
     * @param ORM|Entity $Classe O ORM que deseja fazer o filtro
     * @param array      $where  Where padrão para inicializar o where
     */
    public function __construct(
        private ORM|Entity $Classe,
        private array $where = []
    ) {
    }

    public function where(): array
    {
        return $this->where;
    }

    /**
     * Se vai filtrar para o publicado padrão do sistema ou não
     *
     * @param  string $propriedade Propriedade com o botão para validar
     * @return self
     */
    public function publicado(string $propriedade = 'publicado'): self
    {
        if (!$this->iniciado($propriedade)) {
            return $this;
        }

        $publicado = $this->Classe->$propriedade;
        if (!($publicado instanceof Botao) || !$publicado->valido()) {
            return $this;
        }

        if ($publicado->valor() == 'nao') {
            $this->manual([
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', 1]
            ]);
            return $this;
        }

        $this->manual([
            [
                'OR',
                ['data_inicio', 'null'],
                ['data_inicio', ''],
                ['data_inicio', '<=', hoje() . ' 23:59:59'],
            ],
            [
                'OR',
                ['data_final', 'null'],
                ['data_final', ''],
                ['data_final', '>=', hoje()],
            ],
            ['status', 1]
        ]);
        return $this;
    }

    public function publicadoNao()
    {
        $this->manual([
            'OR',
            ['data_inicio', '>', hoje()],
            ['data_final', '<', hoje()],
            ['status', '!=', 1]
        ]);
        return $this;
    }

    public function publicadoSim()
    {
        $this->manual([
            [
                'OR',
                ['data_inicio', 'null'],
                ['data_inicio', ''],
                ['data_inicio', '<=', hoje() . ' 23:59:59'],
            ],
            [
                'OR',
                ['data_final', 'null'],
                ['data_final', ''],
                ['data_final', '>=', hoje()],
            ],
            ['status', 1]
        ]);
        return $this;
    }

    /**
     * Executa apenas se o valor da propriedade tenha sido iniciada ou não
     *
     * @param  string  $propriedade Nome da propriedade
     * @param  bool    $iniciado    Se deve ser executada quando a propriedade for iniciada ou não
     * @param  Closure $callback
     * @return self
     */
    public function seIniciado(string $propriedade, bool $iniciado = true, Closure $callback = null): self
    {
        if (
            ($this->iniciado($propriedade) && !$iniciado) ||
            (!$this->iniciado($propriedade) && $iniciado)
        ) {
            return $this;
        }
        $this->executarCallback(callback: $callback);
        return $this;
    }

    /**
     * Executa apenas se o valor da propriedade não for vazia
     *
     * @param  string  $propriedade Nome da propriedade
     * @param  Closure $callback
     * @return self
     */
    public function naoVazio(string $propriedade, Closure $callback = null): self
    {
        if (!$this->iniciado($propriedade) || empty($this->Classe->$propriedade)) {
            return $this;
        }
        $this->executarCallback(callback: $callback);
        return $this;
    }

    /**
     * Executa apenas se o valor da propriedade passada for diferente do valor informado
     *
     * @param  string  $propriedade Nome da propriedade
     * @param  mixed   $valor       Valor que quer verificar se é diferente
     * @param  Closure $callback
     * @return self
     */
    public function seDiferente(string $propriedade, mixed $valor = null, Closure $callback = null): self
    {
        $this->seIgualDiferente(propriedade: $propriedade, valor: $valor, callback: $callback, igual: false);
        return $this;
    }

    /**
     * Executa apenas se o valor da propriedade passada for igual do valor informado
     *
     * @param string  $propriedade Nome da propriedade
     * @param mixed   $valor       Valor que quer verificar se é igual
     * @param Closure $callback
     */
    public function seIgual(string $propriedade, mixed $valor = null, Closure $callback = null): self
    {
        $this->seIgualDiferente(propriedade: $propriedade, valor: $valor, callback: $callback);
        return $this;
    }

    /**
     * Executa apenas for um ModuleInterface ou StatusInterface
     *
     * @param  string  $propriedade Nome da propriedade
     * @param  bool    $valido      Se vai executar quando valido ou invalido
     * @param  Closure $callback
     * @return self
     */
    public function seValido(string $propriedade, bool $valido = true, Closure $callback = null): self
    {
        if (!$this->iniciado($propriedade) ||
            (
                !($this->Classe->$propriedade instanceof StatusInterface) &&
                !($this->Classe->$propriedade instanceof ModuleInterface)
            )
        ) {
            return $this;
        }
        if ($this->Classe->$propriedade->valido() !== $valido) {
            return $this;
        }
        $this->executarCallback(callback: $callback);
        return $this;
    }

    /**
     * Executa apenas se for um botão
     *
     * @param  string  $propriedade Nome da propriedade
     * @param  bool    $sim         Se vai validar se o valor é sim ou nao
     * @param  Closure $callback
     * @return self
     */
    public function seBotao(string $propriedade, bool $sim = true, Closure $callback = null): self
    {
        if (!$this->iniciado($propriedade) || !($this->Classe->$propriedade instanceof Botao)) {
            return $this;
        }
        $valor = $this->Classe->$propriedade->valor();
        if (!$this->Classe->$propriedade->valido() || ($valor != 'sim' && $sim) || ($valor != 'nao' && !$sim)) {
            return $this;
        }
        $this->executarCallback(callback: $callback);
        return $this;
    }

    /**
     * Passa um where manual seguindo o padrão do FW
     *
     * @param  array $where Where que deseja passar
     * @return self
     */
    public function manual(array $where): self
    {
        $this->adicionarWhere($where);
        return $this;
    }

    /**
     * Adicionar uma linha no where
     *
     * @param  string      $propriedade Nome da propriedade
     * @param  string      $condicao    Condição que deseja usar, por padrão: =
     * @param  string|null $campo       Campo caso ele seja diferente do nome da propriedade
     * @return self
     */
    public function linha(string $propriedade, string $condicao = '=', ?string $campo = null, mixed $valor = null): self
    {
        if (empty($valor) && !$this->iniciado($propriedade)) {
            return $this;
        }
        $valor = is_null($valor) ? $this->Classe->$propriedade : $valor;

        if (
            (($valor instanceof StatusInterface || $valor instanceof ModuleInterface) && !$valor->valido()) ||
            empty($valor)
        ) {
            return $this;
        }

        $condicao = strCaixaBaixa($condicao);
        if ($condicao == 'like%%') {
            $condicao = 'like';
            $valor = '%' . $valor . '%';
        } elseif ($condicao == 'like%') {
            $condicao = 'like';
            $valor = '%' . $valor;
        } elseif ($condicao == 'like %') {
            $condicao = 'like';
            $valor = $valor . '%';
        } elseif ($condicao == 'notlike%%') {
            $condicao = 'notlike';
            $valor = '%' . $valor . '%';
        } elseif ($condicao == 'notlike%') {
            $condicao = 'notlike';
            $valor = '%' . $valor;
        } elseif ($condicao == 'notlike %') {
            $condicao = 'notlike';
            $valor = $valor . '%';
        }

        $campo = !empty($campo) ? $campo : $propriedade;
        $this->adicionarWhere([$campo, $condicao, $valor]);
        return $this;
    }

    /**
     * Cria um between padrão do sistema de data
     *
     * @param  string      $data1 Primeira data
     * @param  string      $data2 Segunda data
     * @param  string|null $campo Nome do campo, caso não informado, vai remover o _de da data1, ex: data_criacao_de => data_criacao
     * @return self
     */
    public function data(string $data1 = null, string $data2 = null, string $campo = null): self
    {
        if (is_null($campo)) {
            $campo = preg_replace('/\_de$/', '', $data1);
        }

        $dataDe = $this->iniciado($data1) ? $this->pegarValor($data1) : '';
        $dataAte = $this->iniciado($data2) ? $this->pegarValor($data2) : '';

        if ($dataDe && $dataAte) {
            $this->adicionarWhere([
                $campo,
                'between',
                [$dataDe, $dataAte . ' 23:59:59']
            ]);
        } elseif ($dataDe) {
            $this->adicionarWhere([$campo, '>=', $dataDe]);
        } elseif ($dataAte) {
            $this->adicionarWhere([$campo, '<=', $dataAte]);
        }

        return $this;
    }

    /**
     * Cria um grupo novo
     *
     * @param  string  $separador Separador do grupo
     * @param  Closure $callback  Callback
     * @return self
     */
    public function grupo(string $separador = 'AND', Closure $callback = null): self
    {
        $this->whereGrupo = [$separador];
        $this->grupo = true;
        call_user_func($callback);
        $this->where[] = $this->whereGrupo;
        $this->whereGrupo = [];
        $this->grupo = false;
        return $this;
    }

    /**
     * Executa se a propriedade for vazia ou não
     *
     * @param  string       $propriedade
     * @param  boolean      $vazio
     * @param  Closure|null $callback
     * @return self
     */
    public function seVazio(string $propriedade, bool $vazio = true, Closure $callback = null): self
    {
        if (!$this->iniciado($propriedade)) {
            return $this;
        }

        $propValor = $this->pegarValor(propriedade: $propriedade);

        if ((empty($propValor) && !$vazio) || (!empty($propValor) && $vazio)) {
            return $this;
        }

        $this->executarCallback(callback: $callback);
        return $this;
    }

    /**
     * Executa se a propriedade estiver no "inArray
     *
     * @param  string       $propriedade
     * @param  array      $lista
     * @param  Closure|null $callback
     * @return self
     */
    public function seInArray(string $propriedade, array $lista, Closure $callback = null): self
    {
        if (!$this->iniciado($propriedade)) {
            return $this;
        }

        $propValor = $this->pegarValor(propriedade: $propriedade);

        if (empty($propValor) || !in_array($propValor, $lista)) {
            return $this;
        }

        $this->executarCallback(callback: $callback);
        return $this;
    }

    private function seIgualDiferente(string $propriedade, mixed $valor = null, Closure $callback = null, bool $igual = true)
    {
        if (!$this->iniciado($propriedade)) {
            return;
        }

        $propValor = $this->pegarValor(propriedade: $propriedade);

        if (($igual && $propValor !== $valor) || (!$igual && $propValor === $valor)) {
            return $this;
        }

        $this->executarCallback(callback: $callback);
    }

    private function executarCallback(Closure $callback)
    {
        $this->whereTemp = [];
        $this->temp = true;
        call_user_func($callback);
        $this->where[] = $this->whereTemp;
        $this->whereTemp = [];
        $this->temp = false;
    }

    private function pegarValor(string $propriedade)
    {
        $valor = $this->Classe->$propriedade;
        if ($valor instanceof ModuleInterface) {
            $valor = $valor->valido() ? $valor->banco() : null;
        } elseif ($valor instanceof StatusInterface) {
            $valor = $valor->valido() ? $valor->numero() : null;
        }
        return $valor;
    }

    private function adicionarWhere($where)
    {
        if ($this->grupo) {
            $this->whereGrupo[] = $where;
            return;
        }
        if ($this->temp) {
            $this->whereTemp[] = $where;
            return;
        }
        $this->where[] = $where;
    }

    private function iniciado(string $propriedade): bool
    {
        $propriedade = explode('.', $propriedade)[0] ?? null;
        if (is_null($propriedade) || !property_exists($this->Classe, $propriedade)) {
            return false;
        }
        $propriedade = new \ReflectionProperty($this->Classe, $propriedade);
        return $propriedade->isInitialized($this->Classe);
    }
}
