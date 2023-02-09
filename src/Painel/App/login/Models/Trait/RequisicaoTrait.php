<?php

namespace PainelApp\login\Models\Trait;

use Helpers\ApiHelper;

trait RequisicaoTrait
{
    private function fazerRequisicao()
    {
        $Api = new ApiHelper('login:painel');
        $this->token = $Api
            ->validar('Ocorreu um erro ao fazer login, por favor, tente novamente.')
            ->body($this->body)
            ->post('/login/painel')
            ->object();
    }
}
