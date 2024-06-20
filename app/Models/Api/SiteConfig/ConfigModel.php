<?php

namespace App\Models\Api\SiteConfig;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Interface\ModelListarInterface;

final class ConfigModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SITE_CONFIG;
    protected Pagina $pagina;
    protected string $empresa = '';

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo_painel', 'titulo',
                'link_site', 'data_criacao', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina())
            ->read();

        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }
        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = [
                'id_admin_empresa', (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
                    ->pegarIdPeloUuid($this->empresa)
            ];
        }
        return $where;
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'            => $r->uuid,
                'titulo'        => $r->titulo_painel,
                'titulo_painel' => $r->titulo_painel,
                'link_site'     => strDominio($r->link_site),
                'data_criacao'  => $r->data_criacao,
                'status'        => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
