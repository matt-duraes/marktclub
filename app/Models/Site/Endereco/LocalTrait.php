<?php

namespace App\Models\Site\Endereco;

trait LocalTrait
{
    public function pegarLocal(string $local)
    {
        $lista = [
            'loja' => 'parceiro_loja'
        ];
        return $lista[$local] ?? '';
    }
}
