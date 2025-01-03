<?php

namespace App\Models\Api\Galapagos\Api;

use App\Classes\Galapagos\Lead\Status;
use App\Models\Api\Galapagos\Lead\LeadEntity;

final class Redirect
{
    public string $link;

    public function __construct(
        LeadEntity $Lead
    ) {
        $token = (new Token(
            $Lead->nome,
            $Lead->telefone,
            $Lead->email
        ))->token;

        if (empty($token)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao enviar seus dados, por favor, tente novamente.');
        }

        $Lead->status(new Status(Status::NOVO));
        $Lead->salvar();

        $this->link = env('GALAPAGOS_API_LINK_REDIRECT') . '?email=' . urlencode($Lead->email) . '&ti=isi&tokenIntegracao=' . $token;
    }
}
