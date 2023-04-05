<?php

use App\Classes\UsuarioCliente\Helper;

$Painel = new PainelConfig\Visualizar('solicitacao_voucher');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Parceiro', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('parceiro', 'Parceiro foi deletado e não existe mais')
            ->linha('parceiro->titulo', 'Nome');
    });

    $Painel->bloco(titulo: 'Usuário', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario', 'Usuário foi deletado e não existe mais')
            ->linha('usuario->nome', 'Nome')
            ->botao('usuario_link', 'Ver usuário', link: LINK . '/app/visualizar/usuario-cliente/->usuario->id', permissao: Helper::PERMISSAO_VISUALIZAR);
    });

    $Painel->bloco(titulo: 'Dados do voucher', callback: function () use ($Painel) {
        $Painel
            ->linha('data_criacao', 'Data de criação', formatar: 'datahora')
            ->linha('data_validacao', 'Data de validação', formatar: 'datahora')
            ->linha('data_vencimento', 'Data de vencimento', formatar: 'data')
            ->linha('status', 'Status');
    });
});

// Lista de status
$Painel->replace(campo: 'tipo', lista: [
    'voucher' => 'Voucher',
    'declaracao' => 'Declaração',
]);
$Painel->replace(campo: 'status', lista: [
    'criado' => 'Criado',
    'validado' => 'Validado',
]);

return $Painel;
