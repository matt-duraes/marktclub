<?php

use PainelConfig\Download;

$Painel = new Download('parceiro_externo');

$Painel
    ->bloco('Dados gerais', function () use ($Painel) {
        $Painel
            ->campo('titulo_interno', 'Título')
            ->campo('id_dono_equipe', 'Quem cadastrou')
            ->campo('tipo_indicador', 'Indicador')
            ->campo('categoria_principal', 'Categoria')
            ->campo('status', 'Status');
    })
    ->bloco('Datas', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data do cadastro')
            ->campo('data_publicacao', 'Data da publicação')
            ->campo('data_cancelado', 'Data de cancelamento');
    })
    ->bloco('Cancelamento', function () use ($Painel) {
        $Painel
            ->campo('cancelar_motivo', 'Motivo do cancelamento');
    });

return $Painel;
