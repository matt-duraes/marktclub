<?php

namespace App\Helpers;

use Helpers\CurlHelper;

final class PontoCvsHelper
{
    private string $link;
    private string $login;
    private string $senha;

    public function __construct()
    {
        $this->link = env('CVS_API_LINK', '');
        $this->login = env('CVS_API_LOGIN', '');
        $this->senha = env('CVS_API_SENHA', '');
    }

    /**
     * Valida se o usuário tem a quantidade de pontos desejada
     *
     * @param   int     $ponto  Quantidade de ponto solicitada
     * @param   int     $cpf    CPF do usuário
     * @return  bool
     */
    public function validarQuantidadePonto(int $ponto, int $cpf): bool
    {
        try {
            $pontoUsuario = $this->buscarPontosDoUsuario($cpf);
            return $ponto <= $pontoUsuario;
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar seu limite de pontos.', status: 500);
        }
    }

    /**
     * Valida se o usuário tem a quantidade de pontos desejada
     *
     * @param   int     $ponto  Quantidade de ponto solicitada
     * @param   int     $cpf    CPF do usuário
     * @return  bool
     */
    public function validarUsuario(int $cpf): bool
    {
        try {
            return $this->verificarUsuarioValido($cpf);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao validar o Usuario.', status: 500);
        }
    }

    /**
     * Busca a quantidade de pontos do usuário
     * @param   int     $cpf    CPF do usuário
     * @return  int|string      Quantidade de pontos ou "-" quando der erro
     */
    public function buscarPontos(int $cpf): int|string
    {
        try {
            return $this->buscarPontosDoUsuario($cpf);
        } catch (\Throwable) {
            return '-';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function buscarPontosDoUsuario(int $cpf): int
    {
        $Curl = new CurlHelper($this->link);
        $dado = $Curl
            ->header([
                'Content-Type' => 'application/json; charset=utf-8;',
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->senha)
            ])
            ->parametro(['cpf' => $cpf])
            ->get('/bonus/extrato')->object();

        if (
            !object_key_exists('count', $dado) ||
            !object_key_exists('rows', $dado) ||
            !object_key_exists('saldo', $dado)
            ) {
            mensagemStatus(500);
        }

        return $dado->saldo[0]->saldo;
    }

    private function verificarUsuarioValido(int $cpf): int
    {
        $Curl = new CurlHelper($this->link);
        $dado = $Curl->parametro([
            'xcpf' => $cpf,
        ])->post('/return_clube_vantagem')->object();

        ppe($dado);

        if (
            !object_key_exists('count', $dado) ||
            !object_key_exists('rows', $dado) ||
            !object_key_exists('saldo', $dado)
            ) {
            mensagemStatus(500);
        }

        return $dado->saldo[0]->saldo;
    }
}
