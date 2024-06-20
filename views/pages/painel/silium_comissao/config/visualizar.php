<?php

use PainelConfig\Visualizar;
use App\Classes\Silium\StatusComissao;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_comissao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Empresa', function () use ($Painel) {
        $Painel
            ->linha('empresa->titulo', 'Título')
            ->botao(
                'empresa_link',
                'Ver empresa',
                link: LINK . '/app/visualizar/comercial-empresa/empresa->id',
                permissao: Helper::PERMISSAO_EMPRESA
            );
    });

    $Painel->bloco('Usuario', function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Dados da comissão', function () use ($Painel) {
        $Painel
            ->linha('parceiro', 'Parceiro/Loja')
            ->dinheiro('valor_compra', 'Valor de Compra')
            ->dinheiro('comissao_usuario', 'Comissão')
            ->linha('pontuacao', 'Pontuação Adquirida')
            ->data('data_compra', 'Data de Compra');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
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

$Painel->replace('status', (new StatusComissao())->select());

return $Painel;
