<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class CargoModel
{
    public function buscarLotacao(): array
    {
        $cargos = (new ApiHelper('site_cargo:select'))
                    ->get('/site-cargo/select')
                    ->array();
        return $cargos;
    }
}
