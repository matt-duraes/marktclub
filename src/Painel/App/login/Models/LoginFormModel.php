<?php

namespace PainelApp\login\Models;

use PainelApp\login\Models\Trait\ChaveTrait;
use PainelApp\login\Models\Trait\TokenTrait;
use PainelApp\login\Models\Trait\RequisicaoTrait;

final class LoginFormModel implements LoginInterface
{
    use ChaveTrait;
    use RequisicaoTrait;
    use TokenTrait;

    private array $body;

    public function __construct(
        private string $login,
        private string $senha,
    ) {
        $this->setarChaves();
        $this->montarBodyDaRequisicao();
        $this->fazerRequisicao();
    }

    private function montarBodyDaRequisicao()
    {
        $this->body = criptografarDado(
            dado: [
                'login' => $this->login,
                'senha' => $this->senha,
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
