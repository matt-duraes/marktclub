<?php

use Helpers\ApiHelper;
use Helpers\ListaHelper;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

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
            ->cpf(name: 'cpf', label: 'CPF', placeholder: 'CPF')
            ->select(name: 'genero', label: 'Gênero', lista: 'genero')
            ->select(name: 'estado_civil', label: 'Estado Civil', lista: 'estado_civil')
            ->data(name: 'data_nascimento', label: 'Data de nascimento', placeholder: 'Data de Nascimento')
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
        $Lista = new ListaHelper;
        $Api = new ApiHelper(token: true);

        $grupo = $Lista->add('', 'Escolha uma opção')->add(lista: $Api->get('/usuario-grupo/select')->array()['dado'] ?? [])->r();
        $trabalhoEmpresa = $Lista->add('', 'Escolha uma opção')->add(lista: (new TrabalhoEmpresa())->select())->r();
        $trabalhoCargo = $Lista->add('', 'Escolha uma opção')->add(lista: (new TrabalhoCargo())->select())->r();
        $tipoPagamento = $Lista->add('', 'Escolha uma opção')->add(lista: (new TipoPagamento())->select())->r();

        $Painel
            ->numero(name: 'matricula', label: 'Matrícula')
            ->numero(name: 'siape', label: 'SIAPE')
            ->select(name: 'trabalho_empresa', label: 'Local onde trabalha', lista: $trabalhoEmpresa)
            ->select(name: 'trabalho_cargo', label: 'Cargo', lista: $trabalhoCargo)
            ->select(name: 'tipo_pagamento', label: 'Tipo de pagamento', lista: $tipoPagamento)
            ->data(name: 'trabalho_data_inicio', label: 'Data do início do trabalho', placeholder: 'Data do início do trabalho')
            ->select(name: 'grupo', label: 'Grupo', lista: $grupo);
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
