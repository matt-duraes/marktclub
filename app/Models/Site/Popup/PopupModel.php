<?php

namespace App\Models\Site\Popup;

use App\Helpers\ClubeApiHelper;

final class PopupModel extends ClubeApiHelper
{

    public function listarPopup()
    {
        $dado = $this
            ->json([
                'empresa' => sessao('CLUBE')->empresa,
                'status' => 'ativo',
                'pagina' => 1,
            ])
            ->get('/comunicacao-popup')
            ->object();
        return $dado;
    }
}
