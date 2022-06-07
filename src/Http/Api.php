<?php

namespace Http;

use Helpers\CurlHelper;

class Api
{
    private $url;
    private $clientId;
    private $clientSecret;
    private $audience;
    private $parametro;
    private $header;
    private $token;

    public function __construct()
    {
        $this->clientId = env('API_CLIENT_ID');
        $this->clientSecret = env('API_CLIENT_SECRET');
        $this->url = env('API_URL');
        $this->audience = env('API_AUDIENCE');
        $this->parametro = [];
        $this->arquivo = [];
        $this->header = [];
        $this->token = '';
    }

    public function criarToken($scope = '', $forcar = false): String
    {
        if (
            !$forcar && isset($_SESSION['TOKEN_CREDENTIAL']) &&
            isset($_SESSION['TOKEN_CREDENTIAL']->access_token) &&
            isset($_SESSION['TOKEN_CREDENTIAL']->scope) &&
            $_SESSION['TOKEN_CREDENTIAL']->scope == $scope &&
            $this->validarToken($_SESSION['TOKEN_CREDENTIAL']->access_token)
        ) {
            return $_SESSION['TOKEN_CREDENTIAL']->access_token;
        }

        $Curl = new CurlHelper($this->url);
        $token = $Curl->parametro([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'audience' => $this->audience,
            'grant_type' => 'client_credentials',
            'scope' => $scope,
        ])->post('/token')->object();

        if (existeErro($token, 'dado')) {
            return '';
        }
        $_SESSION['TOKEN_CREDENTIAL'] = $token->dado;
        return $token->dado->access_token;
    }

    private function validarToken($token): bool
    {
        if (empty($token)) {
            return false;
        }
        $token = explode('.', $token);
        if (count($token) != 3) {
            return false;
        }
        $token = jsonDecode(\base64_decode($token[1]), true);
        if (!is_array($token) || !isset($token['exp']) || $token['exp'] + 20 <= time()) {
            return false;
        }

        return true;
    }

    public function token($token)
    {
        $this->token = $token;
        return $this;
    }

    public function oauth()
    {
        $this->token = isset($_SESSION['TOKEN']) && isset($_SESSION['TOKEN']->access_token) ? $_SESSION['TOKEN']->access_token : '';
        return $this;
    }

    public function parametro($parametro)
    {
        $this->parametro = $parametro;
        return $this;
    }

    public function arquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    public function header($header)
    {
        $this->header = $header;
        return $this;
    }

    public function post(String $uri)
    {
        return $this->enviar($uri, 'POST');
    }

    public function get(String $uri)
    {
        return $this->enviar($uri, 'GET');
    }

    public function put(String $uri)
    {
        return $this->enviar($uri, 'PUT');
    }

    public function delete(String $uri)
    {
        return $this->enviar($uri, 'DELETE');
    }

    private function enviar($uri, $metodo)
    {
        $Curl = new CurlHelper($this->url);

        $header = $this->montarHeader();
        if ($header) {
            $Curl->header($header);
        }
        if ($this->parametro) {
            $Curl->parametro($this->parametro);
        }
        if ($this->arquivo) {
            $Curl->arquivo($this->arquivo);
        }

        if ($metodo == 'POST') {
            $Curl->post($uri);
        } elseif ($metodo == 'GET') {
            $Curl->get($uri);
        } elseif ($metodo == 'PUT') {
            $Curl->put($uri);
        } elseif ($metodo == 'DELETE') {
            $Curl->delete($uri);
        }

        if (in_array($metodo, ['PUT', 'DELETE']) && $Curl->status() == 204) {
            return (object) ['erro' => false];
        }
        $this->resetar();
        return $Curl->object();
    }

    private function resetar()
    {
        $this->parametro = [];
        $this->arquivo = [];
        $this->header = [];
    }

    private function montarHeader()
    {
        $header = [];
        if (!isset($this->header['Content-Type'])) {
            $header['Content-Type'] = 'application/json';
        }
        if (!empty($this->token)) {
            $header['Authorization'] = 'Bearer ' . $this->token;
        }
        if ($this->header) {
            foreach ($this->header as $ind => $val) {
                $header[$ind] = $val;
            }
        }
        return $header;
    }
}
