<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class ContatoModel
{
    public function __construct(
        private string|array $parceiro
    ) {
    }

    public function buscarDados($tipo)
    {
        $dado = (new ApiHelper('contato:listar'))
            ->json([
                'vinculo' => $this->parceiro,
                'local'   => 'clube',
                'tipo'    => $tipo,
            ])
            ->get('/contato')
            ->array()['dado'] ?? [];
        return $dado;
    }
}
