<?php

use Helpers\ApiHelper;
use Helpers\ListaHelper;

$Painel = new PainelConfig\Add('usuario_cliente');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', function () use ($Painel) {
        $Api = new ApiHelper(token: true);

        $Lista = new ListaHelper;
        $listaSituacao = $Lista->add('', 'Escolha uma opção')->lista(
            $Api->get('/admin/usuario-situacao')->object()->dado ?? [],
            'valor',
            'nome'
        )->r();

        $Painel
            ->input(name: 'nome', label: 'Nome Completo')
            ->cpf(name: 'cpf', label: 'CPF')
            ->select(name: 'genero', label: 'Gênero', lista: 'genero')
            ->select(name: 'estado_civil', label: 'Estado Civil', lista: 'estado_civil')
            ->data(name: 'data_nascimento', label: 'Data de nascimento')
            ->select(name: 'situacao', label: 'Situação', lista: $listaSituacao);
    });
    $Painel->fieldset('Contato', function () use ($Painel) {
        $Painel
            ->email(name: 'email_trabalho', label: 'E-mail de trabalho')
            ->email(name: 'email_pessoal', label: 'E-mail pessoal')
            ->telefone(name: 'telefone_trabalho', label: 'Telefone de trabalho')
            ->telefone(name: 'telefone_pessoal', label: 'Telefone pessoal');
    });

    $Painel->fieldset('Dados do trabalho', callback: function () use ($Painel) {
        $Api = new ApiHelper(token: true);

        $Lista = new ListaHelper;
        $listaOrgao = $Lista->add('', 'Escolha uma opção')->lista(
            $Api->get('/admin/trabalho-orgao')->object()->dado ?? [],
            'valor',
            'nome'
        )->r();
        $listaCargo = $Lista->add('', 'Escolha uma opção')->lista(
            $Api->get('/admin/trabalho-cargo')->object()->dado ?? [],
            'valor',
            'nome'
        )->r();
        $listaTipoPagamento = $Lista->add('', 'Escolha uma opção')->lista(
            $Api->get('/admin/tipo-pagamento')->object()->dado ?? [],
            'valor',
            'nome'
        )->r();

        $Painel
            ->numero(name: 'matricula', label: 'Matrícula')
            ->numero(name: 'siape', label: 'SIAPE')
            ->select(name: 'trabalho_orgao', label: 'Órgão onde trabalha', lista: $listaOrgao)
            ->select(name: 'trabalho_cargo', label: 'Cargo', lista: $listaCargo)
            ->select(name: 'tipo_pagamento', label: 'Tipo de pagamento', lista: $listaTipoPagamento)
            ->data(name: 'trabalho_data_inicio', label: 'Data do início do trabalho');
    });
});

$Painel->coluna(coluna: 3, callback: function () use ($Painel) {
    $Painel->fieldset(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
            ->cep('endereco_cep', label: 'CEP')
            ->input('endereco_logradouro', label: 'Logradouro')
            ->numero('endereco_numero', label: 'Número')
            ->input('endereco_complemento', label: 'Complemento')
            ->input('endereco_bairro', label: 'Bairro')
            ->input(name: 'endereco_cidade', label: 'Cidade')
            ->select(name: 'endereco_estado', label: 'Estado', lista: 'estado');
    });

    $Painel->fieldset('Dados de acesso', function () use ($Painel) {
        $Painel
            ->senha(name: 'senha', label: 'Senha de acesso')
            ->select(name: 'status', label: 'Status', lista: [
                '' => 'Escolha uma opção',
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'bloqueado' => 'Bloqueado'
            ])
            ->switch(name: 'primeiro_acesso', label: 'Primeiro acesso?')
            ->switch(name: 'mudar_senha', label: 'Mudar senha ao logar?');
    });
});

return $Painel;
