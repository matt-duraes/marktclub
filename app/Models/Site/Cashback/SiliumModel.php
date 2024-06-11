<?php

namespace App\Models\Site\Cashback;

use App\Helpers\ClubeApiHelper;

final class SiliumModel extends ClubeApiHelper
{
    public function buscarDados()
    {
        $extratoCompra = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->json([
                'pagina'  => 1,
            ])
            ->get('/silium-comissao')
            ->object();

        $extratoSaque = $this
            ->validar('Não foi possível pegar as solicitações de saque!', status: 404)
            ->json([
                'pagina'  => 1,
            ])
            ->get('/silium-deposito')
            ->object();
        // if (!is_array($dados) || !isset($dados[0]) || !isset($dados[0]->id)) {
        //     return [];
        // }
        $dados = [
            'extrato_compra' => $extratoCompra->dado->lista ?? '',
            'extrato_saque' => $extratoSaque->dado->lista ?? '',
        ];

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
                'pagina'        => 1,
                'quantidade'    => 50,
                'ordem'         => 'mais-novo',
                'usuario'       => $dados['nome'],
                'tipo'          => 'deposito',
                'email'         => $dados['email'],
                'pontuacao'     => $dados['pontos'],
                'nome_titular'  => $dados['titular'],
                'documento_cpf' => $dados['cpf'],
                'banco'         => $dados['banco'],
                'agencia'       => $dados['agencia'],
                'conta'         => $dados['contaBancaria'],
                'tipo_conta'    => $dados['tipoConta']
            ])
            ->post('/silium-deposito')
            ->object();
        if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)) {
            return [];
        };
        return $dado;
    }
}
