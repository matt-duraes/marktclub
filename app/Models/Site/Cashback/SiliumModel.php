<?php

namespace App\Models\Site\Cashback;

use App\Helpers\ClubeApiHelper;

final class SiliumModel extends ClubeApiHelper
{
    public function saldo()
    {
        $dado = $this
            ->validar('Não foi possível resgatar saldo!', status: 404)
            ->get('/silium/saldo')
            ->object();
        return $dado;
    }

    public function extratoConta()
    {
        $dado = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->get('/silium/extrato')
            ->object();

        if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)):
            return [];
        endif;

        return $dado;
    }

    public function extratoSaque()
    {
        $dado = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->get('/silium/saque')
            ->object();
        $dado = [];
        if (!is_array($dado) || !isset($dado[0]) || !isset($dado[0]->id)):
            return [];
        endif;

        return $dado;
    }

    public function solicitarDeposito($dado)
    {
        $titular = $dado['titular'] ?? false;
        $cpf = $dado['cpf'] ?? false;
        $banco = $dado['banco'] ?? false;
        $agencia = $dado['agencia'] ?? false;
        $conta = $dado['conta'] ?? false;
        $tipo_conta = $dado['tipo_conta'] ?? false;

        $validar = (new Validar())
            ->valor($titular, 'Nome do titular')->obrigatorio()->vazio()
            ->valor($cpf, 'CPF do titular')->obrigatorio()->vazio()->cpf()
            ->valor($banco, 'Nome do banco')->obrigatorio()->vazio()
            ->valor($agencia, 'Agencia')->obrigatorio()->vazio()
            ->valor($conta, 'Conta')->obrigatorio()->vazio()
            ->valor($tipo_conta, 'Tipo da conta')->obrigatorio()->vazio()->in_array(['conta-corrente', 'conta-poupanca']);

        if (!$validar->b()):
            return $validar->erro();
        endif;

        $salvar = (new Api())->parametro([
            'titular'    => $titular,
            'cpf'        => $cpf,
            'banco'      => $banco,
            'agencia'    => $agencia,
            'conta'      => $conta,
            'tipo_conta' => $tipo_conta,
        ])->post('/silium/saque')->array();

        if (!is_array($salvar) || !isset($salvar['erro'])):
            return mensagem_erro('Erro ao salvar', 'Ocorreu um erro ao salvar sua solicitação, por favor, tente novamente.', 400);
        elseif (false !== $salvar['erro']):
            return mensagem_erro($salvar['titulo'], $salvar['texto'], 400);
        endif;

        return ['erro' => false];
    }
}
