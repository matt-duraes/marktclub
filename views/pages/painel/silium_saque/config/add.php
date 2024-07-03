<?php

use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoResgate;
use PainelConfig\Add;

$Painel = new Add('silium_saque', $acao);

$TipoConta = new TipoConta();
$TipoResgate = new TipoResgate();

$Painel->coluna(callback: function () use ($Painel, $TipoConta, $TipoResgate) {
    $Painel->fieldset('Informações da Solicitação', function () use ($Painel, $TipoConta, $TipoResgate) {
        $Painel
            ->input(
                name: 'usuario',
                label: 'Identificação Usuário',
                placeholder: 'Insira a identificação do usuário (Id ou CPF)',
                obrigatorio: true,
                acao: 'add'
            )
            ->input(
                name: 'email',
                label: 'E-mail',
                placeholder: 'Insira um e-mail para notificar o resgate',
                obrigatorio: true
            )
            ->input(
                name: 'pontuacao',
                label: 'Pontuação',
                placeholder: 'Insira a pontuação resgatada',
                obrigatorio: true
            )
            ->select(
                name: 'tipo_resgate',
                lista: $TipoResgate->select('Selecione um tipo de resgate'),
                label: 'Tipo de Resgate',
                placeholder: 'Selecione o tipo de resgate solicitado',
                obrigatorio: true
            );
    });

    $Painel->fieldset('Informações Bancárias', function () use ($Painel, $TipoConta, $TipoResgate) {
        $Painel
            ->input(
                name: 'nome_titular',
                label: 'Nome do Titular',
                placeholder: 'Insira o nome do titular da conta'
            )
            ->input(
                name: 'documento_cpf',
                label: 'CPF do Titular',
                placeholder: 'Insira o CPF do titular da conta'
            )
            ->select(
                name: 'tipo_conta',
                lista: $TipoConta->select('Selecione um tipo de conta'),
                label: 'Tipo de Conta',
                placeholder: 'Selecione o tipo de conta'
            )
            ->input(
                name: 'banco',
                label: 'Instituição Financeira',
                placeholder: 'Insira o nome da instituição financeira'
            )
            ->input(
                name: 'agencia',
                label: 'Agência',
                placeholder: 'Insira o número da agência'
            )
            ->input(
                name: 'conta',
                label: 'Conta',
                placeholder: 'Insira o número da conta'
            );
    });
});

return $Painel;
