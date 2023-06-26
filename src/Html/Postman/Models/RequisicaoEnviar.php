<?php

namespace System\Html\Postman\Models;

use Helpers\CryptHelper;

final class RequisicaoEnviar
{
    private $retorno = '';
    private int $status = 0;
    private array $header;

    private string $link;
    private string $clientId;
    private string $secretId;
    private string $redirectUri;
    private string $audience;

    private string $nomeEncode;
    private string $sobreNomeEncode;
    private string $nomeCompletoEncode;
    private string $numeroEncode;
    private string $decimalEncode;
    private string $telefoneEncode;
    private string $emailEncode;
    private string $dataEncode;
    private string $dataPassadaEncode;
    private string $dataFuturaEncode;
    private string $cpfEncode;
    private string $cnpjEncode;
    private string $rgEncode;
    private string $loginEncode;
    private string $senhaEncode;

    private string $uuid;
    private string $nome;
    private string $sobreNome;
    private string $nomeCompleto;
    private string $numero;
    private string $telefone;
    private string $email;
    private string $data;
    private string $dataPassada;
    private string $dataFutura;
    private string $cpf;
    private string $cnpj;
    private string $rg;
    private string $login;
    private string $senha;
    private string $decimal;

    public function __construct($post)
    {
        $token = $post['token'];
        $metodo = $post['metodo'];
        $uri = $post['uri'];
        $parametro = jsonDecode($post['parametro'], true, true);
        $body = jsonDecode($post['body'], true, true);
        $json = jsonDecode($post['json'], true, true);
        $header = jsonDecode($post['header'], true, true);
        $this->header = $header;

        $this->link = env('POSTMAN_API_LINK');
        $this->clientId = env('POSTMAN_API_CLIENT_ID');
        $this->secretId = env('POSTMAN_API_SECRET_ID');
        $this->redirectUri = env('POSTMAN_API_REDIRECT_URI');
        $this->audience = env('POSTMAN_API_AUDIENCE');
        $this->setarDadoRandom();

        if ($token == 'token') {
            $this->criarToken();
        } elseif ($token == 'painel') {
            $this->criarTokenPainel();
        }

        $body = $this->montarParametro($body);
        $parametro = $this->montarParametro($parametro);
        $json = $this->montarParametro($json);

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
        $link = str_replace('{{LINK}}', $this->link, $uri);

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
        foreach ($dado as $ind => $val) {
            if (!str_starts_with($val, '__') || !str_ends_with($val, '__')) {
                continue;
            }
            $nome = preg_replace(['/^\_\_/', '/\_\_$/'], '', $val);
            if (!property_exists($this, $nome)) {
                continue;
            }
            $dado[$ind] = $this->$nome;
        }
        return $dado;
    }
    private function gerarTokenPadrao()
    {
        $token = $this->enviarCurl(
            'POST',
            '{{LINK}}/token',
            [
                'client_id' => $this->clientId,
                'secret_id' => $this->secretId,
                'audience' => $this->audience,
                'grant_type' => 'client_credentials',
                'scope' => ''
            ]
        );
        return $this->pegarToken($token);
    }
    private function criarToken()
    {
        $token = $this->gerarTokenPadrao();
        $this->header['Authorization'] = 'Bearer ' . $token;
    }
    private function criarTokenPainel()
    {
        $header = $this->gerarTokenPadrao();
        $token = $this->enviarCurl(
            metodo: 'POST',
            uri: '{{LINK}}/login/painel',
            body: [
                'login' => $this->loginEncode,
                'senha' => $this->senhaEncode,
                'scope' => '',
                'audience' => $this->audience,
                'redirect_uri' => $this->redirectUri,
                'state' => uuid()
            ],
            header: ['Authorization' => 'Bearer ' . $header]
        );
        $token = $this->pegarToken($token);
        $this->header['Authorization'] = 'Bearer ' . $token;
    }
    private function pegarToken($token)
    {
        $token = jsonDecode($token->retorno, true, true)['dado']['access_token'] ?? '';
        if (!empty($token)) {
            return $token;
        }
        mensagemErro('Erro!', 'Erro ao tentar gerar token.', status: 401);
    }
    private function setarDadoRandom()
    {
        $chave = file_get_contents(ROOT . "/.chave_publica");
        $Crypt = new CryptHelper(chavePublica: $chave);

        $nome = env('POSTMAN_NOME', '');
        $sobreNome = env('POSTMAN_SOBRENOME', '');
        $nomeCompleto = env('POSTMAN_NOME_COMPLETO', '');
        $numero = env('POSTMAN_NUMERO', '');
        $telefone = env('POSTMAN_TELEFONE', '');
        $email = env('POSTMAN_EMAIL', '');
        $data = env('POSTMAN_DATA', '');
        $cpf = env('POSTMAN_CPF', '');
        $cnpj = env('POSTMAN_CNPJ', '');
        $rg = env('POSTMAN_RG', '');
        $login = env('POSTMAN_LOGIN', '');
        $senha = env('POSTMAN_SENHA', '');
        $decimal = env('POSTMAN_DECIMAL', '');

        $this->uuid = uuid();
        $this->nome = !empty($nome) ? $nome : nomeAleatorio();
        $this->sobreNome = !empty($sobreNome) ? $sobreNome : sobreNomeAleatorio();
        $this->nomeCompleto = !empty($nomeCompleto) ? $nomeCompleto : nomeCompletoAleatorio();
        $this->numero = !empty($numero) ? $numero : numeroAleatorio();
        $this->decimal = !empty($decimal) ? $decimal : numeroAleatorio(1, 999) . '.' . numeroAleatorio(10, 99);
        $this->telefone = !empty($telefone) ? $telefone : telefoneAleatorio();
        $this->email = !empty($email) ? $email : emailAleatorio();
        $this->data = !empty($data) ? $data : date('Y-m-d');
        $this->dataPassada = dataPassadaAleatorio();
        $this->dataFutura = dataFuturaAleatorio();
        $this->cpf = !empty($cpf) ? $cpf : cpfAleatorio();
        $this->cnpj = !empty($cnpj) ? $cnpj : cnpjAleatorio();
        $this->rg = !empty($rg) ? $rg : rgAleatorio();
        $this->login = !empty($login) ? $login : '01234567890';
        $this->senha = !empty($senha) ? $senha : 'Teste@1324';

        $this->nomeEncode = $Crypt->encode(!empty($nome) ? $nome : nomeAleatorio());
        $this->sobreNomeEncode = $Crypt->encode(!empty($sobreNome) ? $sobreNome : sobreNomeAleatorio());
        $this->nomeCompletoEncode = $Crypt->encode(!empty($nomeCompleto) ? $nomeCompleto : nomeCompletoAleatorio());
        $this->numeroEncode = $Crypt->encode(!empty($numero) ? $numero : numeroAleatorio());
        $this->decimalEncode = $Crypt->encode(!empty($decimal) ? $decimal : numeroAleatorio(1, 999) . '.' . numeroAleatorio(10, 99));
        $this->telefoneEncode = $Crypt->encode(!empty($telefone) ? $telefone : telefoneAleatorio());
        $this->emailEncode = $Crypt->encode(!empty($email) ? $email : emailAleatorio());
        $this->dataEncode = $Crypt->encode(!empty($data) ? $data : date('Y-m-d'));
        $this->dataPassadaEncode = $Crypt->encode(dataPassadaAleatorio());
        $this->dataFuturaEncode = $Crypt->encode(dataFuturaAleatorio());
        $this->cpfEncode = $Crypt->encode(!empty($cpf) ? $cpf : cpfAleatorio());
        $this->cnpjEncode = $Crypt->encode(!empty($cnpj) ? $cnpj : cnpjAleatorio());
        $this->rgEncode = $Crypt->encode(!empty($rg) ? $rg : rgAleatorio());
        $this->loginEncode = $Crypt->encode(!empty($login) ? $login : '01234567890');
        $this->senhaEncode = $Crypt->encode(!empty($senha) ? $senha : 'Teste@1324');
    }
}
