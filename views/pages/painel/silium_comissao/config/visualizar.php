<?php

use PainelConfig\Visualizar;
use App\Classes\SiliumComissao\Status;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_comissao');

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
    $Painel->bloco('Usuário', function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Dados da Compra', function () use ($Painel) {
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
});

$Painel->replace('status', $Status->select());

return $Painel;
