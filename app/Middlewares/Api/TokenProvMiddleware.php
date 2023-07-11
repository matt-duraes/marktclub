<?php

namespace App\Middlewares\Api;

final class TokenProvMiddleware
{
    private string $token;

    public function __construct()
    {
        $header = getallheaders();
        $this->token = $header['Authorization'] ??
            $header['authorization'] ??
            $_SERVER['HTTP_AUTHORIZATION'] ??
            '';
    }

    public function token(): bool
    {
        if (empty($this->token)) {
            mensagemStatus(401);
        } elseif ($this->token != 'Bearer ca74f479-5c59-4aa2-9bbd-cd117f471562') {
            mensagemStatus(403);
        }
        return true;
    }
}
