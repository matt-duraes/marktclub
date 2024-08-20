<?php

use Modules\Senha;
use Helpers\ApiHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Situacao;
use App\Classes\UsuarioCliente\Federacao;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

$Painel = new PainelConfig\Add(app: 'usuario_cliente', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', function () use ($Painel) {
        $Painel
            ->input(name: 'nome', label: 'Nome Completo')
            ->cpf(name: 'cpf', label: 'CPF', placeholder: 'CPF')
            ->select(name: 'genero', label: 'Gênero', lista: 'genero')
            ->select(name: 'estado_civil', label: 'Estado Civil', lista: 'estado_civil')
            ->data(name: 'data_nascimento', label: 'Data de nascimento', placeholder: 'Data de Nascimento')
            ->select(name: 'situacao', label: 'Situação', lista: (new Situacao())->select('Escolha uma opção'));
    });
    $Painel->fieldset('Contato', function () use ($Painel) {
        $Painel
            ->email(name: 'email_trabalho', label: 'E-mail de trabalho')
            ->email(name: 'email_pessoal', label: 'E-mail pessoal')
            ->telefone(name: 'telefone_trabalho', label: 'Telefone de trabalho')
            ->telefone(name: 'telefone_pessoal', label: 'Telefone pessoal');
    });

    $Painel->fieldset('Dados do trabalho', callback: function () use ($Painel) {
        $trabalhoEmpresa = (new TrabalhoEmpresa())->select('Escolha um local de trabalho');
        $trabalhoCargo = (new ApiHelper(token: true))->get('/site-cargo/select')->array()['dado'] ?? [];

        $Painel
            ->numero(name: 'matricula', label: 'Matrícula')
            ->numero(name: 'siape', label: 'SIAPE')
            ->select(name: 'trabalho_empresa', label: 'Local onde trabalha', lista: $trabalhoEmpresa)
            ->select(
                name: 'trabalho_cargo',
                lista: !empty($trabalhoCargo) ? $trabalhoCargo : (new TrabalhoCargo())->select('Escolha um cargo'),
                label: 'Cargo'
            )
            ->data(
                name: 'trabalho_data_inicio',
                label: 'Data do início do trabalho',
                placeholder: 'Data do início do trabalho'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset(titulo: 'Dados da empresa', callback: function () use ($Painel) {
        $federacao = (new Federacao())->select('Escolha uma federação');
        $tipoPagamento = (new TipoPagamento())->select('Escolha um pagamento');

        $grupoLista = ['' => 'Escolha uma empresa'];
        $subempresaLista = ['' => 'Escolha uma empresa'];

        $empresaSlug = sessao('EMPRESA.slug');
        if ($empresaSlug == 'marktclub') {
            $Painel
                ->select(
                    name: 'empresa->id',
                    label: 'Empresa',
                    lista: 'empresa',
                    acao: 'add',
                    permissao: Helper::PERMISSAO_EMPRESA
                )
                ->hidden(name: 'empresa->id', acao: 'editar', permissao: Helper::PERMISSAO_EMPRESA);
        } else {
            $grupoLista = (new ApiHelper(token: true))
                ->json([
                    'titulo'  => 'Escolha um grupo',
                    'empresa' => sessao('USUARIO.empresa')
                ])
                ->get('/usuario-grupo/select')
                ->array()['dado'] ?? [];

            $subempresaLista = (new ApiHelper(token: true))
                ->json([
                    'titulo'  => 'Escolha uma subempresa',
                    'empresa' => sessao('USUARIO.empresa')
                ])
                ->get('/comercial-subempresa/select')
                ->array()['dado'] ?? [];
            if (empty(sessao('USUARIO.subempresa'))) {
                $Painel
                    ->select(
                        name: 'subempresa',
                        label: 'Subempresa',
                        lista: $subempresaLista,
                    );
            }
        }
        $Painel
            ->select(name: 'grupo', label: 'Grupo', lista: $grupoLista)
            ->select(name: 'tipo_pagamento', label: 'Tipo de pagamento', lista: $tipoPagamento)
            ->select(name: 'federacao', label: 'Federação', lista: $federacao);
    });

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
            ->senha(
                name: 'senha',
                label: 'Senha de acesso',
                ajuda: Senha::MENSAGEM_FORCA_4
            )
            ->select(name: 'status', label: 'Status', lista: [
                ''          => 'Escolha uma opção',
                'ativo'     => 'Ativo',
                'inativo'   => 'Inativo',
                'bloqueado' => 'Bloqueado'
            ])
            ->switch(name: 'primeiro_acesso', label: 'Primeiro acesso?')
            ->switch(name: 'mudar_senha', label: 'Mudar senha ao logar?');
    });
});
$Painel->js('painel_usuario_cliente_add');

return $Painel;
