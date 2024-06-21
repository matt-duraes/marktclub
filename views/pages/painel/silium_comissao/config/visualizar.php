<?php

use PainelConfig\Visualizar;
use App\Classes\Silium\StatusComissao;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_comissao');

$StatusComissao = new StatusComissao();
$Painel->coluna(callback: function () use ($Painel, $StatusComissao) {
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

    $Painel
        ->status(
            campo: 'status',
            texto: 'Liberar pontuação',
            inArray: [$StatusComissao->nome(StatusComissao::AGUARDANDO)],
            status: StatusComissao::LIBERADO,
            mensagem: 'Tem certeza que deseja creditar a pontuação na conta correspondente?',
            cor: 'verde'
        )->status(
            campo: 'status',
            texto: 'Negar pontuação',
            inArray: [$StatusComissao->nome(StatusComissao::AGUARDANDO)],
            status: StatusComissao::NEGADO,
            mensagem: 'Tem certeza que deseja negar a pontuação?',
            cor: 'vermelho'
        );
});

$Painel->replace('status', $StatusComissao->select());

return $Painel;
