<?php

namespace App\Models\Api\Galapagos\Api;

use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\CurlHelper;

final class Token extends CurlHelper
{
    private string $apiLink;
    private string $apiEmail;
    private string $apiToken1;
    private string $apiToken2;
    public string $token;

    public function __construct(
        private Nome $nome,
        private Telefone $telefone,
        private Email $email,
    ) {
        parent::__construct();
        $this->apiLink = env('GALAPAGOS_API_LINK_TOKEN');
        $this->apiEmail = env('GALAPAGOS_API_EMAIL');
        $this->apiToken1 = env('GALAPAGOS_API_TOKEN_1');
        $this->apiToken2 = env('GALAPAGOS_API_TOKEN_2');
        $this->buscarToken();
    }

    private function buscarToken()
    {
        $token = $this
            ->headerJson()
            ->json([
                'email'          => $this->apiEmail,
                'token1'         => $this->apiToken1,
                'token2'         => $this->apiToken2,
                'jsonIntegracao' => jsonEncode([
                    'tipoIntegracao' => 'inclusaoSiteInstitucional2',
                    'nomeCliente'    => $this->nome->nome(),
                    'celularCliente' => $this->telefone->numero(),
                    'emailCliente'   => $this->email->email(),
                ])
            ])
            ->post($this->apiLink)
            ->string();

        $token = str_replace('"', '', $token);
        if (!validarUuid($token)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao enviar seus dados, por favor, tente novamente.');
        }

        $this->token = $token;
    }
}
