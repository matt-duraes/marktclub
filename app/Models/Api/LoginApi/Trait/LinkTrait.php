<?php

namespace App\Models\Api\LoginApi\Trait;

trait LinkTrait
{
    public function link()
    {
        if (SISTEMA == 'HOMOLOGACAO') {
            return 'https://apiv4homologacao.marktclub.com.br/login/api-ok/' . base64Encode([
                'nome' => $this->dadoUsuario['nome'],
                'data' => agora(),
                'hash' => $this->hash
            ], true);
        }
        return 'https://' . $this->linkClube . '/login/api/' . $this->hash;
    }
}
