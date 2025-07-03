<?php

namespace App\Models\Site\Saude\Endereco;

use App\Helpers\ClubeApiHelper;

final class EstadoModel extends ClubeApiHelper
{
    public array $retorno = [];
    public function __construct()
    {
        parent::__construct();
        $this->retorno = $this
            ->get('/saude-convenio/estado')
            ->array()['dado'] ?? [];
    }
}
