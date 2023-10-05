<?php

namespace System\Html\Postman\Models;

use Helpers\CryptHelper;
use Order\OrderInterface;
use Status\StatusInterface;

final class RequisicaoEnviar
{
    private $retorno = '';
    private array $header;
    private array $variavel;
    private string $link;
    private CryptHelper $Crypt;
    private array $requisicao = [];

    public function __construct($post)
    {
        $token = $post['token'];
        $metodo = $post['metodo'];
        $uri = $post['uri'];
        $scope = $post['scope'];
        $parametro = jsonDecode($post['parametro'], true, true);
        $body = jsonDecode($post['body'], true, true);
        $json = jsonDecode($post['json'], true, true);
        $header = jsonDecode($post['header'], true, true);
        $this->variavel = jsonDecode($post['variavel'], true, true);
        $this->header = $header;

        $chaveNome = env('POSTMAN_CHAVE_PUBLICA', '');
        $chaveNome = !empty($chaveNome) ? $chaveNome : '.chave_publica';
        $chave = file_get_contents(ROOT . '/' . $chaveNome);
        $this->Crypt = new CryptHelper(chavePublica: $chave);
        $this->link = env('POSTMAN_API_LINK', '');

        if ($token == 'token') {
            $this->criarToken($scope);
        } elseif ($token == 'painel') {
            $this->criarTokenPainel($scope);
        }

        $body = $this->montarParametro($body);
        $parametro = $this->montarParametro($parametro);
        $json = $this->montarParametro($json);
        $this->header = $this->montarParametro($this->header);

        $dado = $this->enviarCurl($metodo, $uri, $body, $parametro, $json, $this->header);
        $retorno['retorno'] = $dado->retorno;
        $retorno['codigo_html'] = $dado->status;
        $retorno['requisicao'] = $this->requisicao;
        $this->retorno = $retorno;
    }

    public function retorno()
    {
        return $this->retorno;
    }

    private function enviarCurl(
        string $metodo,
        string $uri,
        ?array $body = null,
        ?array $parametro = null,
        ?array $json = null,
        ?array $header = null
    ) {
        $variavel = $this->variavel;
        $link = str_replace(array_keys($variavel), array_values($variavel), str_replace('{{LINK}}', $this->link, $uri));

        if ($parametro) {
            $parametroFinal = [];
            foreach ($parametro as $ind => $val) {
                $parametroFinal[] = $ind . '=' . urlencode($val);
            }
            $parametroFinal = implode('&', $parametroFinal);
            $link .= str_contains($link, '?') ? '&' . $parametroFinal : '?' . $parametroFinal;
        }

        $requestBody = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($body) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $requestBody = $body;
        } elseif ($json) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, jsonEncode($json));
            $requestBody = $json;
        }
        if ($header) {
            $headerFinal = [];
            foreach ($header as $ind => $val) {
                $headerFinal[] = $ind . ': ' . $val;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerFinal);
        }
        $retorno = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erro = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $this->requisicao = [
            'link'   => $link,
            'body'   => $requestBody,
            'header' => $header,
            'metodo' => $metodo
        ];

        return (object)[
            'retorno' => $retorno,
            'status'  => $status,
        ];
    }

    private function montarParametro($dado)
    {
        if (empty($dado)) {
            return[];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $tipo = $r[0];
            $ind = $r[1];
            $val = $r[2];

            $class = false;
            if (class_exists($val)) {
                $class = new $val();
            }
            if ($class instanceof StatusInterface || $class instanceof OrderInterface) {
                $valor = array_keys($class->select(null));
                $retorno[$ind] = $valor[rand(0, count($valor) - 1)];
                continue;
            }

            if (!str_starts_with($val, '$')) {
                $retorno[$ind] = $this->pegarValor($tipo, $val);
                continue;
            }
            if (str_starts_with($val, '$aleatorio=')) {
                $explode = explode(',', preg_replace('/^\$aleatorio\=/', '', $val));
                $retorno[$ind] = $explode[rand(0, count($explode) - 1)];
                continue;
            } elseif ($val == '$uuid') {
                $retorno[$ind] = uuid();
                continue;
            } elseif ($val == '$hoje') {
                $retorno[$ind] = hoje();
                continue;
            } elseif (str_starts_with($val, '$hoje')) {
                $retorno[$ind] = $this->manipularData(hoje(), $val, 'Y-m-d');
                continue;
            } elseif ($val == '$agora') {
                $retorno[$ind] = agora();
                continue;
            } elseif (str_starts_with($val, '$agora')) {
                $retorno[$ind] = $this->manipularData(agora(), $val, 'Y-m-d H:i:s');
                continue;
            } elseif (array_key_exists($val, $this->variavel)) {
                $retorno[$ind] = $this->variavel[$val];
                continue;
            }

            $propriedade = substr($val, 1);
            $valor = env('POSTMAN_' . strCaixaAlta($propriedade), '');
            if (!empty($valor)) {
                $retorno[$ind] = $this->pegarValor($tipo, $valor);
                continue;
            }
            $funcao = $propriedade . 'Aleatorio';
            if (function_exists($funcao)) {
                $retorno[$ind] = $this->pegarValor($tipo, $funcao());
                continue;
            }
            $retorno[$ind] = '';
        }

        return $retorno;
    }

    private function manipularData($data, $string, $retorno)
    {
        if (str_contains($string, '+')) {
            return dataAdicionar($data, explode('+', $string)[1] ?? 1, 'dia', $retorno);
        } elseif (str_contains($string, '-')) {
            return dataRemover($data, explode('-', $string)[1] ?? 1, 'dia', $retorno);
        }
        return $data;
    }

    private function pegarValor($tipo, $val)
    {
        return $tipo == 'cript' ? $this->Crypt->encode($val) : $val;
    }

    private function gerarTokenPadrao($scope)
    {
        $token = $this->enviarCurl(
            'POST',
            '{{LINK}}/token',
            [
                'client_id'  => env('POSTMAN_API_CLIENT_ID'),
                'secret_id'  => env('POSTMAN_API_SECRET_ID'),
                'audience'   => env('POSTMAN_API_AUDIENCE'),
                'grant_type' => 'client_credentials',
                'scope'      => $scope
            ]
        );

        return $this->pegarToken($token);
    }

    private function criarToken($scope)
    {
        $token = $this->gerarTokenPadrao($scope);
        $this->header[] = ['texto', 'Authorization', 'Bearer ' . $token];
    }

    private function criarTokenPainel($scope)
    {
        $header = $this->gerarTokenPadrao('login:painel');
        $token = $this->enviarCurl(
            metodo: 'POST',
            uri: '{{LINK}}/login/painel',
            body: [
                'login'        => $this->Crypt->encode(env('POSTMAN_LOGIN')),
                'senha'        => $this->Crypt->encode(env('POSTMAN_SENHA')),
                'scope'        => $scope,
                'audience'     => env('POSTMAN_API_AUDIENCE'),
                'redirect_uri' => env('POSTMAN_API_REDIRECT_URI'),
                'state'        => uuid()
            ],
            header: ['Authorization' => 'Bearer ' . $header]
        );
        $token = $this->pegarToken($token);
        $this->header[] = ['texto', 'Authorization', 'Bearer ' . $token];
    }

    private function pegarToken($token)
    {
        $token = jsonDecode($token->retorno, true, true)['dado']['access_token'] ?? '';

        if (!empty($token)) {
            return $token;
        }
        mensagemErro('Erro!', 'Erro ao tentar gerar token.', status: 401);
    }
}
