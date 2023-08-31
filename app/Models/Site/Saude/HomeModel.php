<?php

namespace App\Models\Site\Saude;

use App\Helpers\ClubeApiHelper;

final class HomeModel extends ClubeApiHelper
{
    public string $valor = '';

    public function __construct()
    {
        $unimedVitoria = env('SAUDE_UNIMED_VITORIA', 0);
        $unimedSeguro = env('SAUDE_UNIMED_SEGURO', 0);
        $amil = env('SAUDE_AMIL', 0);

        $valor = 0;
        if (MENU_SAUDE_VITORIA && $valor < $unimedVitoria) {
            $valor = $unimedVitoria;
        }
        if (MENU_SAUDE_AMIL && $valor < $amil) {
            $valor = $amil;
        }
        if (MENU_SAUDE_SEGURO && $valor < $unimedSeguro) {
            $valor = $unimedSeguro;
        }
        if (!empty($valor)) {
            $this->valor = number_format($valor, 2, ',', '.');
        }
    }
}
