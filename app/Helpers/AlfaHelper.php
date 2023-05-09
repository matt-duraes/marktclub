<?php

namespace App\Helpers;

use Erro\Erro;

class AlfaHelper
{
    /**
     * @var string URL da API
     */
    private string $url;
    /**
     * @var string Login da API
     */
    private string $login;
    /**
     * @var string Senha da API
     */
    private string $senha;
    /**
     * @var string Token de validação
     */
    private string $token;
    /**
     * @var string Função na API
     */
    private string $funcao = '';
    /**
     * @var array|string[] Lista de CNPJs bloqueados
     */
    private array $cnpjBloqueado = [];

    /**
     * @throws Erro
     */
    public function __construct()
    {
        $envsAlfa = [
            'ALFA_API_LINK'  => env('ALFA_API_LINK'),
            'ALFA_API_LOGIN' => env('ALFA_API_LOGIN'),
            'ALFA_API_SENHA' => env('ALFA_API_SENHA')
        ];

        foreach ($envsAlfa as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            }
        }

        $this->url = env('ALFA_API_URL');
        $this->login = env('ALFA_API_LOGIN');
        $this->senha = env('ALFA_API_SENHA');

        if (!empty(env('ALFA_API_BLOQUEADO')) && is_string(env('ALFA_API_BLOQUEADO'))) {
            $this->cnpjBloqueado = explode(
                ',',
                preg_replace("/[^0-9]/", '', env('ALFA_API_BLOQUEADO'))
            );
        }

        $this->criarToken();
        $this->pegarFuncao();
    }

    /**
     * @return void
     */
    private function criarToken(): void
    {
        $token = $this->curl('POST', '/login/', [
            'tipo'          => 'senha',
            'identificador' => $this->login,
            'segredo'       => $this->senha
        ]);
        $this->token = array_key_exists('token_jwt', $token) ? $token['token_jwt'] : '';
    }

    /**
     * @param  string  $metodo     Método HTTP da requisição
     * @param  string  $uri        URI
     * @param  array   $dados      Dados para serem enviados
     * @param  array   $cabecalho  Cabeçalho da requisição
     *
     * @return array
     */
    private function curl(string $metodo, string $uri, array $dados = [], array $cabecalho = []): array
    {
        $ch = curl_init();

        $cabecalhoPadrao = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        foreach ($cabecalho as $opcao => $valor) {
            $cabecalhoPadrao[] = $opcao . ': ' . $valor;
        }

        $get = [];
        if (!empty($dados) && $metodo === 'GET') {
            foreach ($dados as $campo => $valor) {
                $get[] = $campo . '=' . $valor;
            }
        }

        $get = !empty($get) ? '?' . implode('&', $get) : '';

        curl_setopt($ch, CURLOPT_URL, $this->url . $uri . $get);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalhoPadrao);

        if (!empty($dados) && $metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        }

        $resposta = curl_exec($ch);
        curl_close($ch);

        $resposta = is_string($resposta) ? json_decode($resposta, true) : [];
        return is_array($resposta) ? $resposta : [];
    }

    /**
     * @return void
     */
    private function pegarFuncao(): void
    {
        $funcao = $this->curl('GET', '/me/funcoes', [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        if (
            array_key_exists('results', $funcao)
            && array_key_exists('0', $funcao['results'])
            && array_key_exists('id', $funcao['results'][0])
        ) {
            $this->funcao = $funcao['results'][0]['id'];
        }
    }

    /**
     * @param  int  $cpf  CPF para validar
     *
     * @return array
     */
    public function validarCpf(int $cpf): array
    {
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        $usuario = $this->curl('GET', '/cep/funcionarios/', [
            'search' => $cpf,
            'fields' => 'id,cadastro_ativo,demissao,empresa__cnpj'
        ], [
            'User-Funcao'   => $this->funcao,
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $existe = array_key_exists('results', $usuario)
            && array_key_exists('0', $usuario['results'])
            && array_keys($usuario['results'][0]) === ['id', 'cadastro_ativo', 'demissao', 'empresa']
            && array_key_exists('cnpj', $usuario['results'][0]['empresa'])
            && !in_array(
                preg_replace(
                    "/[^0-9]/",
                    '',
                    $usuario['results'][0]['empresa']['cnpj']
                ),
                $this->cnpjBloqueado
            );

        if ($existe === false) {
            return [];
        }

        $status = empty($usuario['results'][0]['demissao'])
            && in_array($usuario['results'][0]['cadastro_ativo'], ['primeiro-acesso', 'ativo']);

        return [
            'id'     => $usuario['results'][0]['id'],
            'cnpj'   => $usuario['results'][0]['empresa']['cnpj'],
            'status' => $status
        ];
    }
}
