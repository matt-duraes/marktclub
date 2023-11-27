<?php

use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Visualizar('comercial_subempresa');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados da Subempresa', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Título')
            /*->linha('nome_fantasia', 'Nome Fantasia')
            ->linha('razao_social', 'Razão Social')*/
            ->cnpj('cnpj', 'CNPJ');
    });

    /*$Painel->bloco(titulo: 'Dados do responsável', callback: function () use ($Painel) {
        $Painel
            ->linha('responsavel_nome', 'Nome');
    });*/

    $Painel->bloco(titulo: 'Outros dados', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });
});

$Painel->replace('status', (new Status())->select());

return $Painel;
