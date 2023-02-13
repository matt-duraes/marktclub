<?php

namespace PainelApp\login\Models\Trait;

use Helpers\ApiHelper;

trait ChaveTrait
{
    private string $chavePublica;
    private string $chavePrivada;

    private function setarChaves()
    {
        $Api = new ApiHelper('admin:chave_publica admin:chave_privada');
        $this->chavePublica = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $this->chavePrivada = $Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
    }
}
