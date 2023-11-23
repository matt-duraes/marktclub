<?php

namespace App\Models\Api\ComercialSubempresa;

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Cnpj;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class SubempresaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    protected ?int $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly ?string $titulo = null,
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        if (!empty($this->empresa) && validarUuid($this->empresa, false)) {
            mensagemErro('Campo inválido!', 'A Empresa informada não é válida.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $subempresas = $this
            ->campo([
                'cod', 'id_admin_empresa', 'nome_fantasia', 'cnpj', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $subempresas->lista = $this->montarRetorno($subempresas->lista);
        return $subempresas;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $wherePadrao = !empty($this->ormWherePadrao)
            ? $this->ormWherePadrao
            : [['id_admin_empresa', '<>', 'NULL']];
        $where = array_merge(
            $wherePadrao, [['status', (new Status(Status::ATIVO))->numero()]]
        );
        if (!empty($this->titulo)) {
            $where[] = [
                'OR',
                [
                    ['titulo', 'LIKE', "%$this->titulo%"],
                    ['razao_social', 'LIKE', "%$this->titulo%"],
                    ['nome_fantasia', 'LIKE', "%$this->titulo%"]
                ]
            ];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataInicio->date(), $this->dataFinal->date() . ' 23:59:59']
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date() . ' 23:59:59'];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function montarRetorno(array $subempresas): array
    {
        if (empty($subempresas)) {
            return $subempresas;
        }

        $OrmHelper = new OrmHelper($this->ormTabela);
        $Status = new Status();
        $retorno = [];
        foreach ($subempresas as $subempresa) {
            $empresa = $OrmHelper->pegarPrimeiroRegistro(
                ['id', $subempresa->id_admin_empresa],
                ['nome_fantasia'],
                'object'
            );
            $retorno[] = [
                'id'               => $subempresa->cod,
                'empresa_matriz'   => [
                    'nome_fantasia' => $empresa->nome_fantasia
                ],
                'nome_fantasia'    => $subempresa->nome_fantasia,
                'cnpj'             => (new Cnpj($subempresa->cnpj))->cnpj(),
                'status'           => $Status->indice($subempresa->status),
                'data_criacao'     => $subempresa->data_criacao,
                'data_atualizacao' => $subempresa->data_atualizacao
            ];
        }
        return $retorno;
    }
}
