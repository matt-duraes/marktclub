<?php

namespace App\Models\Site\Cashback;

use DateTime;
use App\Helpers\ClubeApiHelper;
use App\Classes\Silium\TipoConta;

final class SiliumModel extends ClubeApiHelper
{
    public function buscarDados()
    {
        $extratoCompra = $this->extratoCompra();
        $extratoSaque = $this->extratoSaque();
        $saldo = 15000;
        $dados = [
            'extrato_compra' => $extratoCompra,
            'extrato_saque'  => $extratoSaque,
            'saldo' => $saldo
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
            ->json([
                'pagina'  => 1,
                'usuario' => sessao('USUARIO.id')
            ])
            ->get('/silium-saque')
            ->object();
        return $dado->dado->lista;
    }

    public function solicitarDeposito($dados)
    {
        $dado = $this
            ->validar('Ocorreu um erro ao fazer a solicitação')
            ->body([
                'usuario' => sessao('USUARIO.id'),
                'email'         => $dados['email'],
                'pontuacao'     => $dados['pontos'],
                'nome_titular'  => $dados['titular'],
                'documento_cpf' => $dados['cpf'],
                'banco'         => $dados['banco'],
                'agencia'       => $dados['agencia'],
                'conta'         => $dados['contaBancaria'],
                'tipo_conta'    => $dados['tipoConta'],
            ])
            ->post('/silium-saque')
            ->object();
        return $dado;
    }
}
