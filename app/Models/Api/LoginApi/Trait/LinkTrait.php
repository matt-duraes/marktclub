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

        $dominio = str_replace(['https://', 'http://'], '', $this->linkClube);
        $link = 'https://' . $dominio . '/login/api/' . $this->hash;
        if (
            SISTEMA == 'HOMOLOGACAO' &&
            !in_array($dominio, ['cfmmais-hom.cfm.org.br', 'digiohml.youhuul.com', 'uberhml.youhuul.com'])
        ) {
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
