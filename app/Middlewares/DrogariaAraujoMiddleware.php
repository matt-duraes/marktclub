<?php

namespace App\Middlewares;

use App\Models\Api\ApiToken\Trait\TokenTrait;
use Erro\Excecao;

final class DrogariaAraujoMiddleware
{
    use TokenTrait;

    private const TOKEN = 'b887b5ea-6256-4410-a118-7c1d594a97fd';
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
     * @return void
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
     * @return void
     * @throws Excecao
     */
    private function validarTokenEnviado(): void
    {
        $mensagem = '';
        if (empty($this->token)) {
            $mensagem = 'Middleware Token - Token vazio.';
        } elseif (!validarUuid(self::TOKEN)) {
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
        if ($this->token !== self::TOKEN) {
            mensagemStatus(
                401,
                localhost: 'Middleware Token - Não foi possível validar seu token'
            );
        }
        return true;
    }
}
