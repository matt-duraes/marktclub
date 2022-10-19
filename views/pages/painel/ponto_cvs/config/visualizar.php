<?php

use App\Classes\PontoCvs\Status;

$Painel = new PainelConfig\Visualizar('ponto_cvs');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Usuário', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario', 'Usuário foi deletado e não existe mais')
            ->linha('usuario->matricula', 'Matricula')
            ->linha('usuario->nome', 'Nome')
            ->linha('usuario->cpf', 'CPF')
            ->linha('usuario->email', 'E-mail')
            ->linha('usuario->telefone', 'Telefone')
            ->linha('usuario->credito', 'Total créditos')
            ->linha('usuario->debito', 'Total débitos')
            ->linha('usuario->saldo', 'Saldo')
            ->botao('usuario_link', 'Ver usuário', link: LINK . '/app/visualizar/usuario-cliente/->usuario->id');
    });

    $Painel->bloco(titulo: 'Dados do voucher', callback: function () use ($Painel) {
        $Painel
            ->linha('ponto_solicitado', 'Ponto solicitado')
            ->linha('voucher', 'Voucher')
            ->linha('mensagem', 'Mensagem')
            ->linha('data_solicitacao', 'Data de solicitação')
            ->linha('data_atualizacao', 'Data de atualização')
            ->linha('data_voucher', 'Data de emissão')
            ->linha('status', 'Status');
    });
});

// Lista de status
$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
