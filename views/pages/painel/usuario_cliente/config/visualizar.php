<?php

use Helpers\ApiHelper;
use Helpers\ListaHelper;

$Painel = new PainelConfig\Visualizar('usuario_cliente');

$Painel
    ->imagemRedonda('imagem')
    ->titulo('nome')
    ->subTitulo(['!email_pessoal', '!email_trabalho'])
    ->margin(40);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->cpf('cpf', 'CPF')
            ->linha('rg', 'RG')
            ->linha('estado_civil', 'Estado Civil')
            ->linha('genero', 'Gênero')
            ->data('data_nascimento', 'Data de nascimento');
    });

    $Painel->bloco(titulo: 'Dados de trabalho', callback: function () use ($Painel) {
        $Painel
            ->linha('matricula', 'Matrícula')
            ->linha('siape', 'SIAPE')
            ->linha('trabalho_orgao', 'Local de trabalho')
            ->linha('trabalho_cargo', 'Cargo')
            ->linha('trabalho_data_inicio', 'Data exercício')
            ->linha('tipo_pagamento', 'Tipo de pagamento')
            ->linha('contrato_siape', 'Contrato')
            ->contar('pagamento', 'Pagamento aberto?')
            ->botao(
                'pagamento',
                texto: 'Gerenciar pagamento',
                id: 'botao_gerenciar_pagamento'
            );
    });

    $Painel->bloco(titulo: 'Contato', callback: function () use ($Painel) {
        $Painel
            ->email('email_trabalho', 'E-mail de trabalho')
            ->email('email_pessoal', 'E-mail pessoal')
            ->email('email_funcional', 'E-mail funcional')
            ->telefone('telefone_trabalho', 'Telefone de trabalho')
            ->telefone('telefone_pessoal', 'Telefone pessoal');
    });

    $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
            ->linha('endereco_logradouro', 'Logradouro')
            ->linha('endereco_numero', 'Número')
            ->linha('endereco_complemento', 'Complemento')
            ->linha('endereco_bairro', 'Bairro')
            ->linha('endereco_cidade', 'Cidade')
            ->linha('endereco_estado', 'Estado')
            ->cep('endereco_cep', 'CEP');
    });

    $Painel->bloco(titulo: 'Dados de acesso', callback: function () use ($Painel) {
        $Painel
            ->linha(['cpf', 'email_pessoal', 'email_trabalho'], 'Login')
            ->checked('possui_senha', 'Possui senha?')
            ->checked('primeiro_acesso', 'Primeiro acesso?')
            ->checked('mensagem', 'Aceita mensagem?')
            ->checked('mudar_senha', 'Mudar Senha?')
            ->linha('status', 'Status');
    });
});

$Painel->include('pagamento', campo: 'pagamento');
$Painel->include('dependente', campo: 'dependente');

$Painel->css('painel_usuario_cliente_Views__visualizar');
$Painel->js('painel_usuario_cliente_Views__visualizar');


$Api = new ApiHelper(token: true);
$Lista = new ListaHelper;

// Lista de tipo de pagamento
$Painel->replace(campo: 'tipo_pagamento', lista: $Lista->lista(
    $Api->get('/admin/tipo-pagamento')->object()->dado ?? [],
    'valor',
    'nome'
)->r());
// Lista de orgão de trabalho
$Painel->replace(campo: 'trabalho_orgao', lista: $Lista->lista(
    $Api->get('/admin/trabalho-orgao')->object()->dado ?? [],
    'valor',
    'nome'
)->r());
// Lista de tipo de cargos
$Painel->replace(campo: 'trabalho_cargo', lista: $Lista->lista(
    $Api->get('/admin/trabalho-cargo')->object()->dado ?? [],
    'valor',
    'nome'
)->r());
// Lista de status
$Painel->replace(campo: 'status', lista: [
    'ativo' => 'Ativo',
    'inativo' => 'Inativo',
    'bloqueado' => 'Bloqueado',
    'indicado' => 'Indicado'
]);

return $Painel;
