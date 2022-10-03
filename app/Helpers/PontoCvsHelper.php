<?php

namespace App\Helpers;

use Helpers\CurlHelper;
use stdClass;

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
            $dadoUsuario = $this->buscarUsuario($cpf)->dados_socio[0];
            $pontoUsuario = $dadoUsuario->saldo;
            return $ponto <= $pontoUsuario;
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar seu limite de pontos.', status: 500);
        }
    }

    /**
     * Valida se o usuário é um usuário valido no banco
     *
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
     * @return  stdClass|string      Classe de pontos ou "-" quando der erro
     */
    public function buscarPontos(int $cpf): stdClass|string
    {
        try {
            $dadoUsuario = $this->buscarUsuario($cpf);
            return $dadoUsuario->dados_socio[0];
        } catch (\Throwable) {
            return '-';
        }
    }

    /**
     * Busca o extrato do usuário
     * @param   int     $cpf    CPF do usuário
     * @return  array|string      Array com extratos ou "-" quando der erro
     */
    public function buscarExtrato(int $cpf): array|string
    {
        try {
            $dadoUsuario = $this->buscarUsuario($cpf);
            return $dadoUsuario->extrato;
        } catch (\Throwable) {
            return '-';
        }
    }

    /**
     * Busca o extrato do usuário
     * @param   int     $cpf    CPF do usuário
     * @return  bool
     */
    public function enviarSolicitacaoPonto(int $cpf, int $ponto): bool
    {
        return $this->salvarSolicitacao($cpf, $ponto);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function buscarUsuario(int $cpf): stdClass
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
            !object_key_exists('dados_socio', $dado) ||
            !object_key_exists('extrato', $dado)
            ) {
            mensagemStatus(500);
        }

        return $dado;
    }

    private function verificarUsuarioValido(int $cpf): int
    {
        $Curl = new CurlHelper($this->link);
        $dado = $Curl
            ->header([
                'Content-Type' => 'application/json;charset=utf-8;',
            ])
            ->json([
                'xcpf' => "$cpf",
            ])->post('/return_clube_vantagem')->object();

        if (
            !object_key_exists('d', $dado)
            ) {
            mensagemStatus(500);
        }

        if($dado->d == '["retorno:1"]')
        {
            return true;
        }

        return false;
    }

    private function salvarSolicitacao(int $cpf, int $quantidade): bool
    {
        $usuario = $this->buscarPontos($cpf);

        if (
            !object_key_exists('matricula', $usuario) ||
            !object_key_exists('tipo_socio', $usuario) ||
            empty($usuario->matricula)||
            empty($usuario->tipo_socio)
            ) {
            mensagemStatus(500);
        }

        $Curl = new CurlHelper($this->link);
        $dado = $Curl
            ->header([
                'Content-Type' => 'application/json;charset=utf-8;',
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->senha)
            ])
            ->parametro([
                'matricula' => "$usuario->matricula",
                'tipo_socio' => $usuario->tipo_socio == "SOCIO" ? 1 : 2,
                'premio' => $usuario->tipo_socio == "SOCIO" ? 34 : 35,
                'quantidade' => $quantidade,
            ])->post('/bonus/solicita')->object();
            
        if (object_key_exists('erro', $dado)) {
            mensagemErro('Erro!', $dado->erro, status: 500);
        } else if (!object_key_exists('sucesso', $dado) && !object_key_exists('erro', $dado)) {
            mensagemStatus(500);
        }

        return true;
    }
}
