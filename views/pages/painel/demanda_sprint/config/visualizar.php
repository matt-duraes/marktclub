<?php

$Painel = new PainelConfig\Visualizar('demanda_sprint');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados da sprint', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Titulo')
            ->data('data_inicio', 'Início da sprint')
            ->data('data_final', 'Final da sprint')
            ->linha('texto_inicio', 'História')
            ->linha('texto_final', 'Solução');
    });
});

$Painel->js('painel_demanda_sprint_visualizar');
$Painel->css('painel_demanda_sprint_visualizar');
$Painel->include('demanda');
$Painel->editar('status', 'nova');

$Painel
    ->botaoDestaque(
        campo: 'status',
        texto: 'Iniciar sprint',
        cor: 'verde',
        inArray: ['nova'],
        id: 'botao_sprint_iniciar'
    )
    ->botaoDestaque(
        campo: 'status',
        texto: 'Concluir sprint',
        cor: 'verde',
        inArray: ['andamento'],
        id: 'botao_sprint_concluir'
    )
    ->botaoDestaque(
        campo: 'status',
        texto: 'Cancelar sprint',
        cor: 'vermelho',
        id: 'botao_sprint_cancelar',
        inArray: ['andamento']
    );

return $Painel;
