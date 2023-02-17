<?php

namespace PainelApp\login\Models;

use stdClass;
use Helpers\SocialHelper;
use PainelApp\login\Models\LoginInterface;
use PainelApp\login\Models\Trait\ChaveTrait;
use PainelApp\login\Models\Trait\TokenTrait;
use PainelApp\login\Models\Trait\RequisicaoTrait;

final class LoginSocialModel implements LoginInterface
{
    use ChaveTrait;
    use RequisicaoTrait;
    use TokenTrait;

    private stdClass $token;
    private string $redeSocialId;
    private array $body;

    public function __construct(
        private string $rede,
        private string $id,
        private string $accessToken,
        private string $code,
    ) {
        $this->setarChaves();

        $this->pegarIdRedeSocial();
        $this->montarBodyDaRequisicao();
        $this->fazerRequisicao();
    }

    private function pegarIdRedeSocial()
    {
        $this->redeSocialId = (new SocialHelper(
            rede: $this->rede,
            id: $this->id,
            token: $this->accessToken,
            code: $this->code
        ))->id();
    }
    private function montarBodyDaRequisicao()
    {
        $this->body = criptografarDado(
            dado: [
                $this->rede => $this->redeSocialId,
                'scope' => '',
                'audience' => env('API_AUDIENCE', ''),
                'redirect_uri' => env('API_REDIRECT_URI', ''),
                'state' => uuid()
            ],
            criptografia: ['login', 'senha'],
            chave: $this->chavePublica
        );
    }
}
