<?php

namespace App\Middlewares;

use App\Models\Api\ApiToken\Trait\TokenTrait;
use Erro\Excecao;

final class DrogariaAraujoMiddleware
{
    use TokenTrait;

    private array $headers;
    private string|bool $token;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->headers = getallheaders();
        $this->pegarTokenEnviado();
        $this->validarTokenEnviado();
    }

    /**
     */
    private function pegarTokenEnviado(): void
    {
        $this->token = false;
        if (!empty($this->headers['Authorization'])) {
            $this->token = $this->headers['Authorization'];
        } elseif (!empty($this->headers['authorization'])) {
            $this->token = $this->headers['authorization'];
        } elseif (!empty($this->headers['HTTP_AUTHORIZATION'])) {
            $this->token = $this->headers['HTTP_AUTHORIZATION'];
        }
    }

    /**
     * @throws Excecao
     */
    private function validarTokenEnviado(): void
    {
        $mensagem = '';
        if (empty($this->token)) {
            $mensagem = 'Middleware Token - Token vazio.';
        } elseif (!validarUuid(env('DROGARIA_ARAUJO_KEY', ''))) {
            $mensagem = 'Middleware Token - Token inválido.';
        }

        if (!empty($mensagem)) {
            mensagemErro(
                'Token inválido!',
                'Envie um token válido para autenticação.',
                401,
                localhost: $mensagem
            );
        }
    }

    /**
     * @throws Excecao
     */
    public function token()
    {
        if ($this->token !== env('DROGARIA_ARAUJO_KEY', '')) {
            mensagemStatus(
                401,
                localhost: 'Middleware Token - Não foi possível validar seu token'
            );
        }
        return true;
    }
}
