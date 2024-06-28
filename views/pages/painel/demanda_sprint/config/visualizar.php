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

$Painel
    ->botaoDestaque(
        campo: 'status',
        texto: 'Iniciar sprint',
        cor: 'verde',
        id: 'botao_sprint_iniciar',
        inArray: ['novo']
    )
    ->botaoDestaque(
        campo: 'status',
        texto: 'Concluir sprint',
        cor: 'verde',
        id: 'botao_sprint_concluir',
        inArray: ['andamento']
    )
    ->botaoDestaque(
        campo: 'status',
        texto: 'Cancelar sprint',
        cor: 'vermelho',
        id: 'botao_sprint_cancelar',
        inArray: ['andamento']
    );

return $Painel;
