<?php

namespace ORM;

use Erro\Erro;
use Erro\Excecao;
use Modules\DataHora;
use ReflectionObject;
use ReflectionProperty;
use ORM\Trait\MudouTrait;
use ORM\Trait\SetGetTrait;
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
    use SetGetTrait;

    public string $id = '';
    public ?DataHora $data_criacao = null;
    public ?DataHora $data_atualizacao = null;
    private array $ormRelacionado = [];
    protected array $ormBuscar = ['id'];
    protected array $ormSalvar = [];
    protected array $ormInsert = [];
    protected array $ormUpdate = [];
    protected ?array $ormDiff = null;
    private bool $cancelarSalvar = false;
    protected string $ormValidarSalvar = '';
    protected string $ormValidarInsert = '';
    protected string $ormValidarUpdate = '';
    protected array $ormDeletarArquivo = [];
    protected array $ormSet = [];
    protected array $ormEntityRetorno = [];
    private bool $ormEntityDeletada = false;
    private ?int $ormEntityId = 0;
    private string $ormEntityUuid = '';
    private array $ormPropriedadePublica = [];
    private array $ormPropriedadePrivada = [];
    private array $ormListaAliasReal = [];
    protected array $ormRetornoPadrao = [];
    protected bool $ormEntityExiste = false;
    protected $ormCampoBanco = [];

    /**
     * @param array $option Option aceitos pelo PDO
     * @param array $conn   Option para a conexao podendo ser:
     *                      host, banco, usuario, porta e senha.
     *                      Caso não informa, será usado o ENV
     */
    public function __construct(array $option = [], array $conn = [])
    {
        parent::__construct($option, $conn);
        $this->ormTipo = 'entity';
        $this->ormMontarPropriedadePublica();
        $this->ormPegarListaParaSet();
        $this->ormPegarListaDeAliasEReal();
        $this->ormCampoBanco = $this->ormPegarColunaBanco();
    }

    /**
     * Pega o retorno padrão do entidade
     */
    public function retorno()
    {
        if (empty($this->ormRetornoPadrao)) {
            return $this->id;
        }
        $retorno = [];
        foreach ($this->ormRetornoPadrao as $indice => $valor) {
            $indice = is_int($indice) ? $valor : $indice;
            $valor = $this->$valor;
            if ($valor instanceof ModuleInterface) {
                $valor = $valor->valor();
            } elseif ($valor instanceof StatusInterface) {
                $valor = $valor->indice();
            }

            $retorno[$indice] = $valor;
        }
        return $retorno;
    }

    /**
     * Cancela um salvar na entidade
     */
    protected function cancelarSalvar()
    {
        $this->cancelarSalvar = true;
    }

    /**
     * Pega o que foi alterado depois de um insert, update ou delete
     */
    public function diff()
    {
        if (is_null($this->ormDiff)) {
            throw new Excecao('Erro!', 'Você só pode usar o diff apois um insert, update ou delete', 403);
        }
        return $this->ormDiff;
    }

    public function foiSetado($propriedade): bool
    {
        try {
            return in_array(strSlug($propriedade, '-'), $this->ormPropriedadeSetada);
        } catch (\Throwable) {
            return false;
        }
    }

    protected function prop(string $propriedade)
    {
        if ($this->ormEntityDeletada) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Esse Entity foi destruido.');
        }
        $retorno = $this->ormEntityRetorno;
        if (array_key_exists($propriedade, $retorno)) {
            return $retorno[$propriedade];
        }
        throw new Erro(mensagem: 'Não existe a prop ' . $propriedade . '.');
    }

    private function ormPegarListaDeSalvarInsertUpdate()
    {
        $acao = empty($this->ormEntityId) ? 'insert' : 'update';
        $lista = [];
        if (property_exists($this, 'ormSalvar') && is_array($this->ormSalvar) && $this->ormSalvar) {
            $lista = array_merge($lista, $this->ormSalvar);
        }
        if (
            $acao == 'insert' && property_exists($this, 'ormInsert') && is_array($this->ormInsert) && $this->ormInsert
        ) {
            $lista = array_merge($lista, $this->ormInsert);
        } elseif (
            $acao == 'update' && property_exists($this, 'ormUpdate') && is_array($this->ormUpdate) && $this->ormUpdate
        ) {
            $lista = array_merge($lista, $this->ormUpdate);
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
        $this->ormListaSet = array_merge($retorno, $this->ormPropriedadePublica);
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
        $this->ormListaAliasReal = $retorno;
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
        $buscar = property_exists($this, 'ormBuscar')
            && is_array($this->ormBuscar) && $this->ormBuscar ? $this->ormBuscar : [];
        if (!$buscar) {
            $this->ormPropriedadePublica = $lista;
            return;
        }

        foreach ($buscar as $ind => $val) {
            $indice = is_int($ind) ? $val : $ind;
            if (!str_starts_with($indice, '!')) {
                $lista[] = $indice;
            }
        }
        $this->ormPropriedadePublica = array_unique($lista);
    }

    private function ormSetarDadoDaEntity(array $dado, string $acao): void
    {
        $lista = $this->ormListaParaMontarEntity();
        if (!array_key_exists('id', $lista) && array_key_exists('id', $dado)) {
            $lista['id'] = 'id';
        }
        if (!array_key_exists('uuid', $lista) && array_key_exists('uuid', $dado)) {
            $lista['uuid'] = 'uuid';
        } elseif (!array_key_exists('uuid', $lista) && array_key_exists('cod', $dado)) {
            $lista['uuid'] = 'cod';
        }

        foreach ($lista as $prop => $campo) {
            if (is_array($campo)) {
                $valor = '';
                foreach ($campo as $subCampo) {
                    if (!array_key_exists($subCampo, $dado)) {
                        mensagemErro(
                            'Erro!',
                            'Ocorre um erro ao buscar registro',
                            status: 500,
                            localhost: 'Não existe o campo ' . $subCampo . ' no banco.'
                        );
                    }
                    if (!empty($dado[$subCampo])) {
                        $valor = $dado[$subCampo];
                        break;
                    }
                }
            } else {
                if (!array_key_exists($campo, $dado)) {
                    mensagemErro(
                        'Erro!',
                        'Ocorre um erro ao buscar registro',
                        status: 500,
                        localhost: 'Não existe o campo ' . $campo . ' no banco.'
                    );
                }
                $valor = is_null($dado[$campo]) ? '' : $dado[$campo];
            }

            $privado = false;
            if (str_starts_with($prop, '!')) {
                $privado = true;
                $prop = substr($prop, 1);
            }

            if ($prop == 'id') {
                $this->ormEntityId = $valor;
                continue;
            } elseif ($prop == 'uuid') {
                $this->id = $valor;
                $this->ormEntityUuid = $valor;
                continue;
            }

            if ($this->ormDadoContemUmSet($prop, $valor, $acao)) {
                continue;
            }

            $valor = $this->ormConverterValorSeForUmModule($prop, $valor);
            $valor = $this->ormConverterValorSeForUmStatus($prop, $valor);
            $valor = $this->ormConverterValorSeForUmaOrdem($prop, $valor);

            if ($privado) {
                $this->ormPropriedadePrivada[$prop] = $valor;
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
        $lista = $this->ormBuscar ?? [];
        $listaJoin = $this->ormRelacionado;
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
        } elseif ($module && $valor instanceof StatusInterface) {
            return $valor->numero();
        }
        return $valor;
    }

    private function ormPegarArquivoParaDeletar(array $dado, bool $todos = false): array
    {
        if (!$this->ormDeletarArquivo) {
            return [];
        }

        $lista = [];
        foreach ($this->ormDeletarArquivo as $campo => $diretorio) {
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
