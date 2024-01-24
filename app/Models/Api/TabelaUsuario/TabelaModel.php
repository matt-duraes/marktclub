<?php

namespace App\Models\Api\TabelaUsuario;

use App\Classes\TabelaUsuario\Ordem;
use App\Classes\TabelaUsuario\Status;
use App\Classes\TabelaUsuario\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Http\Request;
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
        protected ?Request $request = null
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
        return $this->ormWherePadrao;
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
                'erro'              => $r->erro,
                'novo'              => $r->novo,
                'atualizado'        => $r->atualizado,
                'tipo'              => $Tipo->indice($r->tipo),
                'status'            => $Status->indice($r->status),
                'data_criacao'      => $r->data_criacao,
                'data_atualizacao'  => $r->data_atualizacao
            ];
        }

        return $retorno;
    }
}
