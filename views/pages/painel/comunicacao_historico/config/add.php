<?php

$Painel = new PainelConfig\Add(app: 'usuario_cliente', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados da publicacao', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->select(name: 'parceiro', label: 'Parceiro', lista: [])
            ->data(name: ['data_inicio', 'data_final'], label: 'Data de publicação', separador: 'até', placeholder: 'Data de publicação');
    });
});

return $Painel;
