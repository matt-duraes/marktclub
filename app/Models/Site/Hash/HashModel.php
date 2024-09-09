<?php

namespace App\Models\Site\Hash;

use Helpers\ApiHelper;

final class HashModel extends ApiHelper
{
    public string $hash = '';


    public function __construct(string $tipo)
    {
        parent::__construct(token: true);
        $this->pegarHash($tipo);
    }

    private function pegarHash(string $tipo)
    {
        $dado = $this
            ->body([
                'usuario' => sessao('USUARIO.id'),
                'tipo'    => $tipo
            ])
            ->post('/usuario-cliente/hash')
            ->object();
        if (!object_key_exists('dado', $dado) || !object_key_exists('hash', $dado->dado)) {
            return;
        }
        $this->hash = base64_encode(jsonEncode([
            'usuario' => sessao('USUARIO.id'),
            'hash'    => $dado->dado->hash
        ]));
    }
}
