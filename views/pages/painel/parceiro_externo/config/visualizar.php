<?php

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;

$Painel = new PainelConfig\Visualizar('parceiro_externo');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Parceiro', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo_interno', 'Título')
            ->linha('categoria_principal', 'Categoria')
            ->dataHora('data_criacao', 'Criado em')
            ->linha('tipo_indicador', 'Tipo indicador')
            ->linha('status', 'Status');
    });
});

$Painel
    ->replace('categoria_principal', (new Categoria())->select())
    ->replace('status', (new Status())->select());

return $Painel;
