<?php

use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\TipoConta;

$Painel = new PainelConfig\Visualizar('silium_saque');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('empresa->titulo', 'Título')
            ->botao(
                'empresa_link',
                'Ver empresa',
                link: LINK . '/app/visualizar/comercial-empresa/empresa->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
            );
    });

    $Painel->bloco('Usuario', callback: function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->linha('usuario->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Dados Bancários', callback: function () use ($Painel) {
        $Painel
            ->linha('nome_titular', 'Nome do Titular')
            ->cpf('documento_cpf', 'CPF do Titular')
            ->linha('tipo_conta', 'Tipo de Conta')
            ->linha('banco', 'Instituição Financeira')
            ->linha('agencia', 'Agência')
            ->linha('conta', 'Conta');
    });

    $Painel->bloco('Outras Informações', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    /*$Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar solicitação',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário', 'Problema'],
            status: Status::FINALIZADO,
            mensagem: 'Tem certeza que deseja fechar essa solicitação?',
            cor: 'verde'
        );*/
});

$Painel->replace('tipo_conta', (new TipoConta())->select());
$Painel->replace('status', (new StatusDeposito())->select());

return $Painel;
