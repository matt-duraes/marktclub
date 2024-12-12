<?php

namespace System\Html\Postman\Models\Trait;

use Helpers\ApiHelper;
use Helpers\CryptHelper;

trait CryptTrait
{
    private CryptHelper $Crypt;

    private function setarCrypt()
    {
        $publicaNome = env('POSTMAN_CHAVE_PUBLICA', '');
        $publicaNome = !empty($publicaNome) ? $publicaNome : '.chave_publica';
        $pathPublica = ROOT . '/chave/' . $publicaNome;
        $chavePublica = file_exists($pathPublica) ? file_get_contents($pathPublica) : '';

        $privadaNome = env('POSTMAN_CHAVE_PRIVADA', '');
        $privadaNome = !empty($privadaNome) ? $privadaNome : '.chave_privada';
        $pathPrivada = ROOT . '/chave/' . $privadaNome;
        $chavePrivada = file_exists($pathPrivada) ? file_get_contents($pathPrivada) : '';
        $this->Crypt = new CryptHelper(chavePublica: $chavePublica, chavePrivada: $chavePrivada);
    }

    private function buscarChavePorToken(string $token)
    {
        $Api = new ApiHelper(token: $token);
        $chavePublica = $Api->get('/admin/chave-publica')->array()['dado']['chave'];
        $chavePrivada = $Api->get('/admin/chave-privada')->array()['dado']['chave'];

        $this->Crypt = new CryptHelper(chavePublica: $chavePublica, chavePrivada: $chavePrivada);
    }
}
