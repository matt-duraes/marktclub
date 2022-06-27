<?php

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
            ->linha('usuario->cpf', 'CPF')
            ->linha('usuario->email', 'E-mail')
            ->linha('usuario->telefone', 'Telefone')
            ->botao('usuario_link', 'Ver usuário', link: LINK . '/app/visualizar/usuario-cliente/->usuario->id');
    });

    $Painel->bloco(titulo: 'Dados do voucher', callback: function () use ($Painel) {
        $Painel
            ->linha('tipo', 'Tipo')
            ->linha('data_criacao', 'Data de criação')
            ->linha('data_atualizacao', 'Data de atualização')
            ->linha('data_validacao', 'Data de validação')
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
