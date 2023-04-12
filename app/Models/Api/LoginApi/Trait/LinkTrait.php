<?php

namespace App\Models\Api\LoginApi\Trait;

use Http\Response;

trait LinkTrait
{
    public function link(): Response
    {
        if ($this->lgpd) {
            return $this->mandarParaTermoLgpd();
        }

        $link = 'https://' . $this->linkClube . '/login/api/' . $this->hash;
        if (SISTEMA == 'HOMOLOGACAO' && !in_array($this->linkClube, ['cfmhml.marktclub.net.br'])) {
            $link = 'https://apiv4homologacao.marktclub.net.br/login/api-ok/' . base64Encode([
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
