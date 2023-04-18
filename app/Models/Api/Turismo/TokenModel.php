<?php

namespace App\Models\Api\Turismo;

use Helpers\CurlHelper;
use Helpers\UserAgentHelper;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class TokenModel extends CurlHelper
{
    private string $partnerId;
    private string $apiRetorno;
    private string $linkWhiteLabel;
    private string $link;
    public function __construct(
        private ClienteEntity $Usuario,
        private string $ip,
        private string $userAgent,
        private string $memoria
    ) {
        parent::__construct(env('MILHAS_API_LINK'));
        $this->partnerId = env('MILHAS_API_PARTNER_ID');
        $this->linkWhiteLabel = env('MILHAS_API_WHITELABEL');
        $this->apiRetorno = env('MILHAS_API_RETORNO') . $this->Usuario->id;

        $token = $this->buscarToken();
        $this->setarLink($token);
    }

    public function pegarLink()
    {
        return $this->link;
    }

    private function buscarToken()
    {
        return $this
            ->header([
                'Content-Type' => 'application/json'
            ])
            ->json([
                'partnerId' => $this->partnerId,
                'customerPartnerId' => $this->Usuario->id,
                'checkCustomerPartnerApi' => $this->apiRetorno,
                'fingerprint' => [
                    'userAgent' => $this->userAgent,
                    'ip' => $this->ip,
                    'language' => 'pt-BR',
                    'timezone' => '-3',
                    'deviceMemory' => $this->memoria,
                    'plataform' => $this->pegarPlataforma()
                ]
            ])
            ->post('/partners/auth')
            ->array();
    }
    private function setarLink(array $token)
    {
        if (!is_array($token) || !array_key_exists('accessToken', $token)) {
            mensagemStatus(401);
        }
        $this->link = $this->linkWhiteLabel . '/?auth=' . $token['accessToken'];
    }
    private function pegarPlataforma(): string
    {
        $Agent = new UserAgentHelper($this->userAgent);
        return $Agent->os();
    }
}
