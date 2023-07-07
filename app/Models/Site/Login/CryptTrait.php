<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;
use Helpers\CryptHelper;

trait CryptTrait
{
    private function setarCrypt()
    {
        $publica = (new ApiHelper('admin:chave_publica'))->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $privada = (new ApiHelper('admin:chave_privada'))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $this->Crypt = new CryptHelper(chavePublica: $publica, chavePrivada: $privada);
    }
}
