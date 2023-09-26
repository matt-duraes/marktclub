<?php

namespace App\Models\Site\Samsung;

use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public function buscar()
    {
        $dado = $this->get('/samsung')->object();
        return (object)[
            'email' => []
        ];
    }
}
