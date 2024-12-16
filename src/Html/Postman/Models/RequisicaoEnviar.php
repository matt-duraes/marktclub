<?php

namespace System\Html\Postman\Models;

use Helpers\CryptHelper;
use Order\OrderInterface;
use Status\StatusInterface;
use System\Html\Postman\Models\Trait\CurlTrait;
use System\Html\Postman\Models\Trait\CryptTrait;
use System\Html\Postman\Models\Trait\TokenCredentialEntityTrait;

final class RequisicaoEnviar
{
    use CurlTrait;
    use CryptTrait;
    use TokenCredentialEntityTrait;

    private string $nomeToken;
    private $retorno = '';
    private array $header;
    private array $variavel = [];
    private string $link;
    private CryptHelper $Crypt;
    private array $requisicao = [];

    public function __construct($post)
    {
        $token = $post['token'];
        $header = jsonDecode($post['header'], true, true);
        $this->variavel = jsonDecode($post['variavel'], true, true);
        $this->header = $header;

        $this->setarCrypt();
        $this->link = env('POSTMAN_API_LINK', '');
        $this->nomeToken = strCaixaAlta('POSTMAN_TOKEN_' . $token);

        $this->iniciarRequest($post);
    }

    private function iniciarRequest($post, bool $repetir = true)
    {
        $token = $post['token'];
        $metodo = $post['metodo'];
        $uri = $post['uri'];
        $scope = $post['scope'];
        $parametro = jsonDecode($post['parametro'], true, true);
        $body = jsonDecode($post['body'], true, true);
        $json = jsonDecode($post['json'], true, true);

        if (sessaoExiste($this->nomeToken) && is_array(sessao($this->nomeToken)) && validarIndiceExiste(sessao($this->nomeToken), ['token', 'publica', 'privada'])) {
            $tokenExistente = sessao($this->nomeToken);
            $this->setarCryptPelaChave($tokenExistente['publica'], $tokenExistente['privada']);
            $this->header[] = ['texto', 'Authorization', 'Bearer ' . $tokenExistente['token'] ?? ''];
        } elseif ($token == 'token') {
            $this->criarToken($scope);
        } elseif ($token == 'painel') {
            $this->criarTokenPainel($scope);
        } elseif (!empty($token) && $token != 'sem_token') {
            $this->verificarClasseExiste($token);
        }

        $body = $this->montarParametro($body);
        $parametro = $this->montarParametro($parametro);
        $json = $this->montarParametro($json);
        $this->header = $this->montarParametro($this->header);

        $dado = $this->enviarCurl($metodo, $uri, $body, $parametro, $json, $this->header);
        if (in_array($dado->status, [401, 403]) && true === $repetir) {
            sessaoDeletar($this->nomeToken);
            $this->iniciarRequest($post, false);
            return;
        }

        $retorno['retorno'] = $dado->retorno;
        $retorno['codigo_html'] = $dado->status;
        $retorno['requisicao'] = $this->requisicao;
        $retorno['header'] = $dado->header;
        $this->retorno = $retorno;
    }

    public function retorno()
    {
        return $this->retorno;
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
        $this->setarTokenComLogin($this->pegarToken($token));
    }

    private function verificarClasseExiste($nome)
    {
        $Token = new TokenCriado($nome);
        if (empty($Token->token)) {
            mensagemErro('Erro!', 'Não foi possível criar o token para: ' . $nome . '.');
        }
        $this->setarTokenComLogin($Token->token);
    }

    private function setarTokenComLogin($token)
    {
        $chave = $this->setarCryptPorToken($token);
        sessao($this->nomeToken, [
            'token'   => $token,
            'publica' => $chave['publica'],
            'privada' => $chave['privada'],
        ]);
        $this->header[] = ['texto', 'Authorization', 'Bearer ' . $token];
    }
}
