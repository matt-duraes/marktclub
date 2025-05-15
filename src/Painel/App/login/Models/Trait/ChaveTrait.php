<?php

namespace PainelApp\login\Models\Trait;

use Helpers\ApiHelper;
use Throwable;

trait ChaveTrait
{
    private string $chavePublica;
    private string $chavePrivada;

    private function setarChaves(bool $token = false)
    {
        if ($token) {
            $Api = new ApiHelper(token: true);
        } else {
            try {
                $Api = new ApiHelper('admin:chave_publica admin:chave_privada');
            } catch (Throwable $th) {
                ppe($th);
            }
        }

        $this->chavePublica = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $this->chavePrivada = $Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
    }
}
