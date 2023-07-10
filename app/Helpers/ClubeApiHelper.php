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
        if (!sessaoExiste('CRYPT_HELPER_CHAVE')) {
            $chave = $this
                ->get('/admin/chave-publica')
                ->object()->dado->chave ?? '';
            sessao('CRYPT_HELPER_CHAVE', $chave);
        }

        $this->Crypt = new CryptHelper(chavePublica: sessao('CRYPT_HELPER_CHAVE'));
    }
}
