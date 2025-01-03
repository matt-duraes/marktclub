<?php

namespace App\Models\Api\Galapagos\Api;

use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\CurlHelper;

final class Token extends CurlHelper{

    private string $link;
    private string $emailValidacao;
    private string $token1;
    private string $token2;
    public string $token;
    public function __construct(
        private Nome $nome,
        private Telefone $celular,
        private Email $email,
    )
    {
        parent::__construct();
        $this->link = env('GALAPAGOS_API_LINK_TOKEN');
        $this->emailValidacao = env('GALAPAGOS_API_EMAIL');
        $this->token1 = env('GALAPAGOS_API_TOKEN_1');
        $this->token2 = env('GALAPAGOS_API_TOKEN_2');
        $this->buscarToken();
    }

    private function buscarToken()
    {
        $dado = $this
            ->debug()
            ->json([
                'email' => $this->emailValidacao,
                'token1' => $this->token1,
                'token2' => $this->token2,
                'jsonIntegracao' => [
                    'tipoIntegracao' => 'inclusaoSiteInstitucional2',
                    'nomeCliente' => $this->nome->nome(),
                    'celularCliente' => $this->celular->numero(),
                    'emailCliente' => $this->email->email(),
                ]
            ])
            ->post($this->link)
            ->object();
        ppe($dado);
    }
}
