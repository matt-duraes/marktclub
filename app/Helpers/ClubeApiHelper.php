<?php

namespace App\Helpers;

use Helpers\ApiHelper;
use Helpers\CryptHelper;

class ClubeApiHelper extends ApiHelper
{
    protected CryptHelper $Crypt;
    protected string $idUsuario;

    public function __construct()
    {
        parent::__construct(token: true);
        $this->idUsuario = sessao('USUARIO.id');
        $this->setarCryptHelper();
    }

    private function setarCryptHelper()
    {
        if (!sessaoExiste('CRYPT_CHAVE_PUBLICA')) {
            $chave = $this
                ->get('/admin/chave-publica')
                ->object()->dado->chave ?? '';
            sessao('CRYPT_CHAVE_PUBLICA', $chave);
        }
        if (!sessaoExiste('CRYPT_CHAVE_PRIVADA')) {
            $chave = $this
                ->get('/admin/chave-privada')
                ->object()->dado->chave ?? '';
            sessao('CRYPT_CHAVE_PRIVADA', $chave);
        }

        $this->Crypt = new CryptHelper(
            chavePublica: sessao('CRYPT_CHAVE_PUBLICA'),
            chavePrivada: sessao('CRYPT_CHAVE_PRIVADA')
        );
    }
}
