<?php

use PainelConfig\Visualizar;
use App\Classes\SiliumComissao\Status;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_comissao');

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
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

    /*
    $Painel
        ->status(
            campo: 'status',
            texto: 'Liberar pontuação',
            inArray: [$Status->nome(Status::NEGADO)],
            status: Status::LIBERADO,
            mensagem: 'Tem certeza que deseja creditar a pontuação na conta correspondente?',
            cor: 'verde'
        )->status(
            campo: 'status',
            texto: 'Negar pontuação',
            inArray: [$Status->nome(Status::LIBERADO)],
            status: Status::NEGADO,
            mensagem: 'Tem certeza que deseja negar a pontuação?',
            cor: 'vermelho'
        );*/
});

$Painel->replace('status', $Status->select());

return $Painel;
