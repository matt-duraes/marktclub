<?php

namespace App\Models\Site\Hash;

use Helpers\ApiHelper;

final class HashModel extends ApiHelper
{
    public string $hash = '';

    public function __construct()
    {
        parent::__construct(token: true);
        $this->pegarHash();
    }

    private function pegarHash()
    {
        $dado = $this
            ->body([
                'usuario' => sessao('USUARIO.id'),
                'tipo'    => 'campanha'
            ])
            ->post('/usuario-cliente/hash')
            ->object();
        if (!object_key_exists('dado', $dado) || !object_key_exists('hash', $dado->dado)) {
            return;
        }
        $this->hash = base64_encode(jsonEncode([
            'usuario' => sessao('USUARIO.id'),
            'hash'    => $this->hash
        ]));
    }
}
