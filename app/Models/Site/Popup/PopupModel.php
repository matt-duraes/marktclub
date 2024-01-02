<?php

namespace App\Models\Site\Popup;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;
use App\Helpers\LinkClubeHelper;

final class PopupModel extends ClubeApiHelper
{
    public function __construct()
    {
        parent::__construct();
        if (sessaoExiste('POPUP_PROMOCAO')) {
            return;
        }
        $dado = $this
            ->json([
                'publicado'    => Botao::SIM,
                'usuario_tipo' => sessao('USUARIO.tipo'),
                'empresa'      => CLUBE_ID,
                'pagina'       => 1
            ])
            ->get('/comercial-popup')
        ->object()->dado->lista ?? [];
        sessao('POPUP_PROMOCAO', $this->montarPopup($dado));
    }

    private function montarPopup($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $r->botao_link = (new LinkClubeHelper($r->botao_link))->link;
            $retorno[] = $r;
        }
        return $retorno;
    }

    public function listar()
    {
        $lista = sessao('POPUP_PROMOCAO');
        if (!$lista) {
            return [];
        }
        $primeiro = array_shift($lista);
        sessao('POPUP_PROMOCAO', $lista);
        return $primeiro;
    }
}
