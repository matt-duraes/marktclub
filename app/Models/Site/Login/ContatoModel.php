<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class ContatoModel
{
    public function __construct(
        private string $parceiro
    ) {
    }

    public function buscarDados($tipo)
    {
        return (new ApiHelper('contato:listar'))
            ->json([
                'vinculo' => $this->parceiro,
                'local'   => 'clube',
                'tipo'    => $tipo,
            ])
            ->get('/contato')
            ->array()['dado'] ?? [];
    }
}
