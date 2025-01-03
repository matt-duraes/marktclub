<?php

namespace App\Models\Api\Galapagos\Api;

use App\Models\Api\Galapagos\Lead\LeadEntity;

final class Redirect {
    public string $link;

    public function __construct(
        LeadEntity $Lead
    )
    {
        $token = (new Token(
            $Lead->nome,
            $Lead->celular,
            $Lead->email
        ))->token;

        if(empty($token)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao enviar seus dados, por favor, tente novamente.');
        }

        $this->link = env('GALAPAGOS_API_LINK_REDIRECT') . '?email=' . env('GALAPAGOS_API_EMAIL') . '&ti=isi&tokenIntegracao=' . $token;
    }
}
