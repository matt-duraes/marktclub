<?php

use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Visualizar('solicitacao_declaracao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro.titulo', 'Nome')
            ->linha('modelo', 'Modelo')
            ->linha('versao', 'Versão');
    });

    $Painel->bloco(titulo: 'Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('usuario.nome', 'Nome');
    });

    $Painel->bloco(titulo: 'Dados da declaração', callback: function () use ($Painel) {
        $Painel
            ->linha('data_criacao', 'Data de criação', formatar: 'datahora')
            ->linha('data_atualizacao', 'Data de atualização', formatar: 'datahora')
            ->linha('status', 'Status');
    });
});

$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
