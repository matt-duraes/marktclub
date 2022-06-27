<?php

namespace App\Models\Api\ApiToken\Trait;

trait RedirectUriTrait
{
    private function pegarRedirectUri(string $redirectUri, array $permitido): string
    {
        $this->validarUri($redirectUri);
        $this->validarSeUriTemPermissao($redirectUri, $permitido);

        return $redirectUri;
    }

    private function validarUri(string $uri): void
    {
        if (empty($uri)) {
            mensagemErro('Erro!', 'Não foi enviado uma URI.', 400);
        } else if (validarUrl($uri)) {
            mensagemErro('Erro!', 'A URI informada não tem um formato válido.', 400);
        }
        return;
    }

    private function validarSeUriTemPermissao(string $uri, array $permitido): void
    {
        $host = $this->pegarHostDaUri($uri);

        if (!in_array($host, $permitido)) {
            mensagemErro('Sem permissão!', 'A URI informada não tem permissão para acessar esse APP!', 403);
        }
        return;
    }

    private function pegarHostDaUri(string $uri): string
    {
        return explode('/', preg_replace('/^https?\:\/\//', '', $uri))[0];
    }
}
