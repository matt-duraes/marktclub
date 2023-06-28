<?php

namespace App\Helpers\Alfa;

use Erro\Erro;
use Erro\Excecao;
use Helpers\CurlHelper;

class AlfaLogin
{
    /**
     * @var string Link da API
     */
    private string $link;
    /**
     * @var string Usuário da API
     */
    private string $usuario;
    /**
     * @var string Senha da API
     */
    private string $senha;
    /**
     * @var string Token de usuário
     */
    private string $token;
    /**
     * @var string Função na API
     */
    private string $funcao = '';
    /**
     * @var string[] Lista de CNPJs bloqueados
     */
    private array $cnpjBloqueado = [];

    /**
     * @throws Erro
     * @throws Excecao
     */
    public function __construct()
    {
        $envsAlfa = [
            'ALFA_API_LOGIN_LINK'    => env('ALFA_API_LOGIN_LINK'),
            'ALFA_API_LOGIN_USUARIO' => env('ALFA_API_LOGIN_USUARIO'),
            'ALFA_API_LOGIN_SENHA'   => env('ALFA_API_LOGIN_SENHA')
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

        $this->link = env('ALFA_API_LOGIN_LINK');
        $this->usuario = env('ALFA_API_LOGIN_USUARIO');
        $this->senha = env('ALFA_API_LOGIN_SENHA');

        if (!empty(env('ALFA_API_LOGIN_BLOQUEADOS')) && is_string(env('ALFA_API_LOGIN_BLOQUEADOS'))) {
            $this->cnpjBloqueado = explode(
                ',',
                preg_replace("/[^0-9]/", '', env('ALFA_API_LOGIN_BLOQUEADOS'))
            );
        }

        $this->criarToken();
        $this->pegarFuncao();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function criarToken(): void
    {
        $token = (new CurlHelper())
            ->post($this->link . '/login/')
            ->headerJson()
            ->body([
                'tipo'          => 'senha',
                'identificador' => $this->usuario,
                'segredo'       => $this->senha
            ])
            ->array();
        $this->token = array_key_exists('token_jwt', $token) ? $token['token_jwt'] : '';
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarFuncao(): void
    {
        $funcao = (new CurlHelper())
            ->get($this->link . '/me/funcoes')
            ->headerJson()
            ->header([
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->array();

        if (
            array_key_exists('results', $funcao)
            && array_key_exists('0', $funcao['results'])
            && array_key_exists('id', $funcao['results'][0])
        ) {
            $this->funcao = $funcao['results'][0]['id'];
        }
    }

    /**
     * @param  string  $cpf  CPF para validar
     *
     * @return array
     * @throws Excecao
     */
    public function validarCpf(string $cpf): array
    {
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        $usuario = (new CurlHelper())
            ->get($this->link . '/cep/funcionarios/')
            ->headerJson()
            ->header([
                'User-Funcao'   => $this->funcao,
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->body([
                'search' => $cpf,
                'fields' => 'id,cadastro_ativo,demissao,empresa__cnpj'
            ])
            ->array();

        $existe = array_key_exists('results', $usuario)
            && array_key_exists('0', $usuario['results'])
            && array_key_exists('id', $usuario['results'][0])
            && array_key_exists('cadastro_ativo', $usuario['results'][0])
            && array_key_exists('demissao', $usuario['results'][0])
            && array_key_exists('empresa', $usuario['results'][0])
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
