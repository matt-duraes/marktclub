<?php

namespace App\Models\Site\Cashback;

use App\Helpers\ClubeApiHelper;

final class SiliumModel extends ClubeApiHelper
{
    public function buscarDados()
    {
        $dados = $this
            ->validar('Não foi possível resgatar saldo!', status: 404)
            ->get('/silium-dados')
            ->object();
        if (!is_array($dados) || !isset($dados[0]) || !isset($dados[0]->id)) {
            return [];
        }
        return $dados;
    }

    public function saldo()
    {
        $dado = $this
            ->validar('Não foi possível resgatar saldo!', status: 404)
            ->get('/silium-saldo')
            ->object();
        return $dado;
    }

    public function extratoCompra()
    {
        $dado = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->json([
                'pagina'  => 1,
                'usuario' => sessao('USUARIO.id')
            ])
            ->get('/silium-comissao')
            ->object();

        return $dado->dado->lista;
    }

    public function extratoSaque()
    {
        $dado = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->get('/silium-saque')
            ->object();

        if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)) {
            return [];
        }

        return $dado;
    }

    public function solicitarDeposito($dados)
    {
        $dado = $this
            ->validar('Ocorreu um erro ao fazer a solicitação')
            ->body([
                'nome'          => $dados['nome'],
                'pontuacao'     => $dados['pontos'],
                'email'         => $dados['email'],
                'nome_titular'  => $dados['titular'],
                'documento_cpf' => $dados['cpf'],
                'banco'         => $dados['banco'],
                'agencia'       => $dados['agencia'],
                'conta'         => $dados['conta'],
                'tipo_conta'    => $dados['tipo_conta']
            ])
            ->post('/silium-deposito')
            ->object();
        if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)) {
            return [];
        };

        return $dado;
    }
}
