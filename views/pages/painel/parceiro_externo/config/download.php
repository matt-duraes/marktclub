<?php

$Painel = new PainelConfig\Download('parceiro_externo');

$Painel
    ->bloco('Dados gerais', function () use ($Painel) {
        $Painel
            ->campo('titulo_interno', 'Título')
            ->campo('id_dono_equipe', 'Quem cadastrou')
            ->campo('categoria', 'Categoria')
            ->campo('status', 'Status');
    })
    ->bloco('Datas', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data do cadastro')
            ->campo('data_publicacao', 'Data da publicação')
            ->campo('data_cancelamento', 'Data de cancelamento');
    })
    ->bloco('Cancelamento', function () use ($Painel) {
        $Painel
            ->campo('cancelar_motivo', 'Motivo do cancelamento');
    });

return $Painel;
