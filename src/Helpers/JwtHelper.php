<?php

namespace Helpers;

use Firebase\JWT\JWT;

final class JwtHelper
{
    /**
     * @param String    $jwt   JWT pra validar ou pegar informações
     */
    public function __construct(
        private string $jwt = ''
    ) {
    }

    /**
     * @param Array     $payload    Array para a criação do payload
     * @param String    $chave      Nome do arquivo da chave privada para criação do JWT
     * @param String    $hash       Hash para criação do JWT caso não use chave privada
     */
    public function encode(array $payload, string $chave = '', string $hash = ''): String
    {
        if (!empty($chave) && file_exists(DIRETORIO_PRIVADO . '/jwt/' . $chave) && file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave)) {
            $tipoCriptografia = 'RS256';
            $key = file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave);
        } elseif (!empty($hash)) {
            $tipoCriptografia = 'HS256';
            $key = $hash;
        } else {
            mensagemErro('Erro!', 'Você precisa passar uma hash para o JWT.', 500);
        }

        try {
            return JWT::encode($payload, $key, $tipoCriptografia, null, ['kid' => uuid()]);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao criar o JWT.', 500);
        }
    }

    /**
     * @param String    $chave      Nome do arquivo da chave pública para decodificar o JWT
     * @param String    $hash       Hash para decodificar o JWT caso não tenha criado com chave privada
     */
    public function validar(string $chave = '', string $hash = ''): Bool
    {
        $jwt = $this->jwt;

        if (!empty($chave) && file_exists(DIRETORIO_PRIVADO . '/jwt/' . $chave . '.pub') && file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave . '.pub')) {
            $tipoCriptografia = 'RS256';
            $key = file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave . '.pub');
        } elseif (!empty($hash)) {
            $tipoCriptografia = 'HS256';
            $key = $hash;
        } else {
            return false;
        }

        try {
            JWT::decode($jwt, $key, [$tipoCriptografia]);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @param String    $campo     Campo caso queira pegar apenas um parâmetro da header
     */
    public function header(String $campo = '')
    {
        $jwt = $this->jwt;
        $explode = explode('.', $jwt);

        if (count($explode) != 3) {
            return [];
        }

        $dado = jsonDecode(\base64_decode($explode[0]), true);
        $dado = is_array($dado) ? $dado : [];
        if (!empty($campo)) {
            return $dado[$campo] ?? '';
        }
        return $dado;
    }

    /**
     * @param String    $campo     Campo caso queira pegar apenas um parâmetro do body
     */
    public function body(String $campo = '')
    {
        $jwt = $this->jwt;
        $explode = explode('.', $jwt);

        if (count($explode) != 3) {
            mensagemErro('Erro!', 'O Token não está em um formáto válido.', 401);
        }

        $dado = jsonDecode(\base64_decode($explode[1]), true);
        if (!is_array($dado)) {
            mensagemErro('Erro!', 'O Token enviado não tem um corpo válido.', 401);
        }

        if (!empty($campo) && array_key_exists($campo, $dado)) {
            return $dado[$campo];
        } else if (!empty($campo)) {
            mensagemErro('Erro!', 'O indice procurado não existe.', 400);
        }

        return $dado;
    }
}
