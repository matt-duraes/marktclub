<?php

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Status;
use PainelConfig\Visualizar;

$Painel = new Visualizar('parceiro_externo');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Parceiro', function () use ($Painel) {
        $Painel
            ->linha('titulo_interno', 'Título')
            ->linha('categoria_principal', 'Categoria')
            ->linha('tipo_indicador', 'Tipo indicador')
            ->dataHora('data_criacao', 'Criado em')
            ->linha('status', 'Status');
    });

    $Painel->bloco('Contato', function () use ($Painel) {
        $Painel
            ->linha('contato->nome', 'Nome')
            ->linha('contato->cpf', 'CPF')
            ->linha('contato->email', 'E-mail')
            ->linha('contato->telefone', 'Telefone');
    });
});

$Painel
    ->replace('categoria_principal', (new Categoria())->select())
    ->replace('status', (new Status())->select());

return $Painel;
