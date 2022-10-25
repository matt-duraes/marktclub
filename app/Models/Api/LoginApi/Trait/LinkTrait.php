<?php

namespace App\Models\Api\LoginApi\Trait;

use Http\Response;

trait LinkTrait
{
    public function link(): Response
    {
        $link = 'https://' . $this->linkClube . '/login/api/' . $this->hash;
        if (SISTEMA == 'HOMOLOGACAO') {
            $link = 'https://apiv4homologacao.marktclub.com.br/login/api-ok/' . base64Encode([
                'nome' => $this->dadoUsuario['nome'],
                'data' => agora(),
                'hash' => $this->hash
            ], true);
        }

        return mensagemSucesso([
            'link' => $link
        ], status: 201);
    }
}
