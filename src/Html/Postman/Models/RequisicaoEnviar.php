<?php

namespace System\Html\Postman\Models;

use Helpers\CryptHelper;

final class RequisicaoEnviar
{
    private $retorno = '';
    private array $header;
    private array $variavel;
    private string $link;
    private CryptHelper $Crypt;

    public function __construct($post)
    {
        $token = $post['token'];
        $metodo = $post['metodo'];
        $uri = $post['uri'];
        $parametro = jsonDecode($post['parametro'], true, true);
        $body = jsonDecode($post['body'], true, true);
        $json = jsonDecode($post['json'], true, true);
        $header = jsonDecode($post['header'], true, true);
        $this->variavel = jsonDecode($post['variavel'], true, true);
        $this->header = $header;

        $chave = file_get_contents(ROOT . "/.chave_publica");
        $this->Crypt = new CryptHelper(chavePublica: $chave);
        $this->link = env('POSTMAN_API_LINK', '');

        if ($token == 'token') {
            $this->criarToken();
        } elseif ($token == 'painel') {
            $this->criarTokenPainel();
        }

        $body = $this->montarParametro($body);
        $parametro = $this->montarParametro($parametro);
        $json = $this->montarParametro($json);
        $this->header = $this->montarParametro($this->header);

        $dado = $this->enviarCurl($metodo, $uri, $body, $parametro, $json, $this->header);
        $retorno['retorno'] = $dado->retorno;
        $retorno['codigo_html'] = $dado->status;
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($body) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } elseif ($json) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, jsonEncode($json));
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

        return (object)[
            'retorno' => $retorno,
            'status' => $status,
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

            if (!str_starts_with($val, '$')) {
                $retorno[$ind] = $this->pegarValor($tipo, $val);
                continue;
            }
            $propriedade = substr($val, 1);
            if ($propriedade == 'uuid') {
                $retorno[$ind] = uuid();
                continue;
            }
            if (array_key_exists($propriedade, $this->variavel)) {
                $retorno[$ind] = $this->variavel[$propriedade];
                continue;
            }
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
    private function pegarValor($tipo, $val)
    {
        return $tipo == 'cript' ? $this->Crypt->encode($val) : $val;
    }
    private function gerarTokenPadrao()
    {
        $token = $this->enviarCurl(
            'POST',
            '{{LINK}}/token',
            [
                'client_id' => env('POSTMAN_API_CLIENT_ID'),
                'secret_id' => env('POSTMAN_API_SECRET_ID'),
                'audience' => env('POSTMAN_API_AUDIENCE'),
                'grant_type' => 'client_credentials',
                'scope' => ''
            ]
        );
        return $this->pegarToken($token);
    }
    private function criarToken()
    {
        $token = $this->gerarTokenPadrao();
        $this->header[] = ['texto', 'Authorization', 'Bearer ' . $token];
    }
    private function criarTokenPainel()
    {
        $header = $this->gerarTokenPadrao();
        $token = $this->enviarCurl(
            metodo: 'POST',
            uri: '{{LINK}}/login/painel',
            body: [
                'login' => $this->Crypt->encode(env('POSTMAN_LOGIN')),
                'senha' => $this->Crypt->encode(env('POSTMAN_SENHA')),
                'scope' => '',
                'audience' => env('POSTMAN_API_AUDIENCE'),
                'redirect_uri' => env('POSTMAN_API_REDIRECT_URI'),
                'state' => uuid()
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
