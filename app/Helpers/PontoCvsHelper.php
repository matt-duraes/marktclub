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
        return 1000;
        // $Curl = new CurlHelper($this->link);
        // $dado = $Curl
        //     ->header([
        //         'Content-Type' => 'application/json',
        //         'Authorization' => base64_encode($this->login . '.' . $this->senha)
        //     ])
        //     ->parametro(['cpf' => $cpf])
        //     ->get('/login')->object();

        // if (existeErro($dado, 'status') || $dado->status != 'sucesso') {
        //     mensagemStatus(500);
        // }

        // return $dado->dado->ponto;
    }
}
