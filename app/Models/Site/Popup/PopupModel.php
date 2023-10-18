<?php

namespace App\Models\Site\Popup;

use App\Helpers\ClubeApiHelper;

final class PopupModel extends ClubeApiHelper
{

    public function buscarPopup()
    {
        $dado = $this
            ->get('/comercial-popup/popup/'.sessao('CLUBE')->empresa)
            ->object();
        return $dado;
    }
}
