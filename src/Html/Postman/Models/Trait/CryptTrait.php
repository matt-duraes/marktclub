<?php

namespace System\Html\Postman\Models\Trait;

use Helpers\CryptHelper;

trait CryptTrait
{
    private CryptHelper $Crypt;

    private function setarCrypt()
    {
        $chaveNome = env('POSTMAN_CHAVE_PUBLICA', '');
        $chaveNome = !empty($chaveNome) ? $chaveNome : '.chave_publica';
        $chave = file_get_contents(ROOT . '/chave/' . $chaveNome);
        $this->Crypt = new CryptHelper(chavePublica: $chave);
    }
}
