<?php

namespace App\Models\Site\Saude;

use App\Helpers\ClubeApiHelper;

final class HomeModel extends ClubeApiHelper
{
    public string $valor = '';

    public function __construct()
    {
        $menu = (object)sessao('CLUBE.menu');
        $unimedVitoria = env('SAUDE_UNIMED_VITORIA', 0);
        $unimedSeguro = env('SAUDE_UNIMED_SEGURO', 0);
        $amil = env('SAUDE_AMIL', 0);

        $valor = 0;
        if ($menu->saude_vitoria && $valor < $unimedVitoria) {
            $valor = $unimedVitoria;
        }
        if ($menu->saude_amil && $valor < $amil) {
            $valor = $amil;
        }
        if ($menu->saude_seguros && $valor < $unimedSeguro) {
            $valor = $unimedSeguro;
        }
        if (!empty($valor)) {
            $this->valor = number_format($valor, 2, ',', '.');
        }
    }
}
