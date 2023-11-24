<?php

use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Add('comercial_subempresa', $acao);

$Painel->coluna(coluna: 2, callback: function () use ($Painel) {
    $Painel->fieldset('Dados da Subempresa', function () use ($Painel) {
        $Painel
            ->select(
                name: 'Empresa Matriz',
                lista: 'empresa',
                label: 'Empresa Matriz'
            )
            ->input(name: 'titulo', label: 'Titulo', placeholder: 'Titulo')
            ->input(name: 'razao_social', label: 'Razão Social', placeholder: 'Razão Social')
            ->input(name: 'nome_fantasia', label: 'Nome Fantasia', placeholder: 'Nome Fantasia')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status'
            );
    });
});

return $Painel;
