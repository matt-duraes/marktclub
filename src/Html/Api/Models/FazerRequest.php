<?php

use Helpers\CryptHelper;

final class FazerRequest
{
    private $retorno = '';
    private int $status = 0;

    private string $link;
    private string $clientId;
    private string $secretId;
    private string $redirectUri;
    private string $audience;

    public function __construct(
        string $token,
        string $metodo,
        string $uri,
        array $parametro,
        array $body,
        private array $header,
        array $json
    ) {
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
        $this->enviarCurl($metodo, $uri, $body, $parametro, $json, $this->header);
    }
    public function retorno()
    {
        return jsonEncode([
            'retorno' => $this->retorno,
            'status' => $this->status
        ]);
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
                $headerFinal[] = $ind . ':' . $val;
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
        )->retorno->token->access_token ?? '';

        if (!empty($token)) {
            return $token;
        }
        mensagemErro('Erro!', 'Erro ao tentar gerar token.', status: 401);
    }
    private function criarToken()
    {
        $token = $this->gerarTokenPadrao();
        $this->header['Authorization'] = 'Bearer ' . $token;
    }
    private function criarTokenPainel()
    {
        $token = $this->gerarTokenPadrao();
        $token = $this->enviarCurl(
            'POST',
            '{{LINK}}/login/painel',
            [
                'login' => env(''),
                'senha',
                'scope',
                'audience',
                'redirect_uri',
                'state' => uuid()
            ]
        );
    }
    private function gerar()
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

        $this->nomeCript = $Crypt->encode(!empty($nome) ? $nome : nomeAleatorio());
        $this->sobreNomeCript = $Crypt->encode(!empty($sobreNome) ? $sobreNome : sobreNomeAleatorio());
        $this->nomeCompletoCript = $Crypt->encode(!empty($nomeCompleto) ? $nomeCompleto : nomeCompletoAleatorio());
        $this->numeroCript = $Crypt->encode(!empty($numero) ? $numero : numeroAleatorio());
        $this->decimalCript = $Crypt->encode(!empty($decimal) ? $decimal : numeroAleatorio(1, 999) . '.' . numeroAleatorio(10, 99));
        $this->telefoneCript = $Crypt->encode(!empty($telefone) ? $telefone : telefoneAleatorio());
        $this->emailCript = $Crypt->encode(!empty($email) ? $email : emailAleatorio());
        $this->dataCript = $Crypt->encode(!empty($data) ? $data : date('Y-m-d'));
        $this->dataPassadaCript = $Crypt->encode(dataPassadaAleatorio());
        $this->dataFuturaCript = $Crypt->encode(dataFuturaAleatorio());
        $this->cpfCript = $Crypt->encode(!empty($cpf) ? $cpf : cpfAleatorio());
        $this->cnpjCript = $Crypt->encode(!empty($cnpj) ? $cnpj : cnpjAleatorio());
        $this->rgCript = $Crypt->encode(!empty($rg) ? $rg : rgAleatorio());
        $this->loginCript = $Crypt->encode(!empty($login) ? $login : '01234567890');
        $this->senhaCript = $Crypt->encode(!empty($senha) ? $senha : 'Teste@1324');
    }
}
