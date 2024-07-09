<?php

namespace App\Models\Site\Cashback;

use App\Helpers\ClubeApiHelper;
use App\Classes\SiliumDeposito\TipoOperacao;

final class SiliumModel extends ClubeApiHelper
{
    public function buscarDados()
    {
        $extratoCompra = $this->extratoCompra();
        $extratoSaque = $this->extratoSaque();
        $saldo = $this->saldo();
        $dados = [
            'extrato_compra' => $extratoCompra,
            'extrato_saque'  => $extratoSaque,
            'saldo'          => $saldo
        ];
        return $dados;
    }

    public function saldo()
    {
        $dado = $this
            ->validar('Não foi possível resgatar saldo!', status: 404)
            ->get('/silium-saldo/' . sessao('USUARIO.id'))
            ->object();
        return $dado->dado->saldo_silium;
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

    public function pontosResgate()
    {
        $dado = $this
            ->validar('Não foi possível pegar os pontos necessários!', status: 404)
            ->get('/silium-admin')
            ->object();
        return $dado->dado;
    }

    public function extratoSaque()
    {
        $dado = $this
            ->json([
                'pagina'        => 1,
                'usuario'       => sessao('USUARIO.id'),
                'tipo_operacao' => TipoOperacao::SAQUE
            ])
            ->get('/silium-deposito')
            ->object();
        return $dado->dado->lista;
    }

    public function solicitarDeposito($dados)
    {
        $dado = $this
            ->validar('Ocorreu um erro ao fazer a solicitação')
            ->body([
                'tipo_operacao' => TipoOperacao::SAQUE,
                'usuario'       => sessao('USUARIO.id'),
                'email'         => $dados['email'],
                'pontuacao'     => $dados['pontos'],
                'nome_titular'  => $dados['titular'],
                'documento_cpf' => $dados['cpf'],
                'banco'         => $dados['banco'],
                'agencia'       => $dados['agencia'],
                'conta'         => $dados['contaBancaria'],
                'tipo_conta'    => $dados['tipoConta'],
                'tipo_resgate'  => $dados['tipoResgate']
            ])
            ->post('/silium-deposito')
            ->object();
        return $dado;
    }
}
