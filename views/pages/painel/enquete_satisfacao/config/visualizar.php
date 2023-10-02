<?php

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;

$Painel = new PainelConfig\Visualizar('enquete_satisfacao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Respostas', callback: function () use ($Painel) {
        $Painel
            ->linha('navegar', 'Navegação')
            ->linha('procura', 'Procurando')
            ->linha('suporte', 'Suporte')
            ->linha('atendimento', 'Atendimento')
            ->array('sistemas_clube', 'Sistemas do Clube')
            ->linha('comentario', 'Comentários')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Visualizado',
            inArray: ['Novo'],
            status: 'visualizada',
            mensagem: 'Tem certeza que deseja alterar para visualizado?',
            cor: 'verde'
        );
});

$Painel->replace(campo: 'navegar', lista: (new Navegar())->select());
$Painel->replace(campo: 'procura', lista: (new Procura())->select());
$Painel->replace(campo: 'suporte', lista: (new Suporte())->select());
$Painel->replace(campo: 'atendimento', lista: (new Atendimento())->select());
$Painel->replace(campo: 'status', lista: (new Status())->select());

return $Painel;
