<?php

namespace App\Models\Api\SiteMenu;

use ORM\ORM;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\SiteMenu\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class MenuModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SITE_MENU;
    public string $empresa = '';
    public Status $status;

    public function listarDados(): array
    {
        $dado = $this
            ->campo(['id', 'uuid', 'id_site_menu', 'titulo', 'tipo', 'link', 'target', 'data_criacao', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order([['id_site_menu', 'ASC'], ['ordem', 'ASC']])
            ->read();
        return $this->montarRetorno($dado);
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->propriedadeExiste('status')) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        $Status = new Status();
        $Target = new Target();
        foreach ($dado as $r) {
            $tipo = $Tipo->indice($r->tipo);
            $menu = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'status' => $Status->indice($r->status)
            ];
            if ($tipo == Tipo::MENU) {
                $menu['link'] = $r->link;
                $menu['target'] = $Target->indice($r->target);
            }
            if ($tipo == Tipo::SUB_MENU) {
                $menu['menu'] = [];
            }
            if (!empty($r->id_site_menu)) {
                $retorno[$r->id_site_menu]['menu'][] = $menu;
                continue;
            } else {
                $menu['tipo'] = $tipo;
            }
            $retorno[$r->id] = $menu;
        }
        return array_values($retorno);
    }
}
