<?php

use App\Classes\UsuarioCliente\GrauParentesco;
use App\Classes\UsuarioCliente\Helper;

$Painel = new PainelConfig\Visualizar('solicitacao_cheque_bonus');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Automóvel', callback: function () use ($Painel) {
        $Painel
            ->linha('automovel->parceiro', 'Parceiro')
            ->linha('automovel->modelo', 'Modelo')
            ->linha('automovel->versao', 'Versão')
            ->linha('automovel->cor', 'Cor')
            ->dinheiro('automovel->valor', 'valor');
    });

    $Painel->bloco('Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->email('usuario->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Dependente', callback: function () use ($Painel) {
        $Painel
            ->linha('dependente->nome', 'Nome')
            ->email('dependente->email_pessoal', 'E-mail')
            ->linha('dependente->rg', 'RG', formatar: 'rg')
            ->cpf('dependente->cpf', 'CPF')
            ->linha('dependente->grau_parentesco', 'Grau de parentesco')
            ->linha('dependente->data_nascimento', 'Data de nascimento', formatar: 'data');
    });

    $Painel->bloco('Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->email('email_pessoal', 'E-mail')
            ->data('data_nascimento', 'Data de nascimento')
            ->linha('rg', 'RG', formatar: 'rg')
            ->cep('endereco_cep', 'CEP')
            ->linha('endereco_logradouro', 'Logradouro')
            ->linha('endereco_numero', 'Número')
            ->linha('endereco_complemento', 'Complemento')
            ->linha('endereco_bairro', 'Bairro')
            ->linha('endereco_cidade', 'Cidade')
            ->linha('endereco_estado', 'Estado');
    });

    $Painel->bloco('Dados do cheque bônus', callback: function () use ($Painel) {
        $Painel
            ->data('data_criacao', 'Data de criação')
            ->data('data_atualizacao', 'Data de atualização')
            ->data('data_termo', 'Data do termo')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviado p/ empresa',
            inArray: ['novo'],
            status: 'enviado-empresa',
            mensagem: 'Tem certeza que deseja alterar o status para enviado para a empresa?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Enviado p/ usuário',
            inArray: ['novo', 'enviado-empresa'],
            status: 'enviado-usuario',
            mensagem: 'Tem certeza que deseja alterar o status para enviado para o usuário?',
            cor: 'verde'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Solicitação com problema',
            inArray: ['novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'problema',
            mensagem: 'Tem certeza que deseja finalizar essa solicitação com o status problema?',
            cor: 'vermelho'
        );

    $Painel
        ->status(
            campo: 'status',
            texto: 'Solicitação finalizada',
            inArray: ['novo', 'enviado-empresa', 'enviado-usuario'],
            status: 'finalizado',
            mensagem: 'Tem certeza que deseja finalizar essa solicitação?',
            cor: 'vermelho'
        );
});

$Painel->replace('dependente->grau_parentesco', (new GrauParentesco())->select());

return $Painel;
