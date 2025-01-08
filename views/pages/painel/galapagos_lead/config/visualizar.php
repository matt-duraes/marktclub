<?php

use App\Classes\Galapagos\Lead\Status;

$Painel = new PainelConfig\Visualizar('galapagos_lead');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('empresa->nome', 'Nome')
            ->botao(
                'empresa_link',
                'Ver empresa',
                link: LINK . '/app/visualizar/comercial-empresa/empresa->id',
                permissao: \App\Classes\ComercialEmpresa\Helper::PERMISSAO_VISUALIZAR
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

    $Painel->bloco('Dados', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->linha('email', 'E-mail')
            ->linha('telefone', 'Telefone')
            ->status('status', 'Status');
    });
});

$Painel->replace('status', (new Status())->select());

return $Painel;
