<?php

namespace App\Models\Api\TabelaUsuario;

use App\Classes\TabelaUsuario\Ordem;
use App\Classes\TabelaUsuario\Status;
use App\Classes\TabelaUsuario\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\OrmHelper;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class TabelaModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SISTEMA_USUARIO;

    public function __construct(
        protected readonly Pagina $pagina = new Pagina(),
        protected readonly Quantidade $quantidade = new Quantidade(),
        protected readonly Ordem $ordem = new Ordem(),
        protected readonly Status $status = new Status(),
        protected readonly Tipo $tipo = new Tipo(),
        protected readonly ?string $empresa = null,
        protected readonly ?string $data_de = null,
        protected readonly ?string $data_ate = null,
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'id_usuario_equipe', 'id_admin_empresa', 'arquivo', 'erro',
                'novo', 'atualizado', 'tipo', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->join('id', 'id_usuario_equipe')
            ->campo([
                'uuid', 'nome_real'
            ], 'equipe')
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid', 'nome_fantasia'
            ], 'empresa')
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);

        return $dado;
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        if (!empty($this->empresa)) {
            $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa);
            $where[] = ['id_admin_empresa', $empresa];
        }
        if (!empty($this->data_de)) {
            $where[] = ['data_criacao', '>=', $this->data_de . ' 00:00:00'];
        }
        if (!empty($this->data_ate)) {
            $where[] = ['data_criacao', '<=', $this->data_ate . ' 23:59:59'];
        }

        return $where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'                => $r->uuid,
                'usuario'           => [
                    'id'    => $r->equipe_uuid,
                    'nome'  => $r->equipe_nome_real
                ],
                'empresa'           => [
                    'id'    => $r->empresa_uuid,
                    'nome'  => $r->empresa_nome_fantasia
                ],
                'arquivo'           => $r->arquivo,
                'erro'              => $r->erro ?? 0,
                'novo'              => $r->novo ?? 0,
                'atualizado'        => $r->atualizado ?? 0,
                'tipo'              => $Tipo->indice($r->tipo),
                'status'            => $Status->indice($r->status),
                'data_criacao'      => $r->data_criacao,
                'data_atualizacao'  => $r->data_atualizacao
            ];
        }

        return $retorno;
    }
}
