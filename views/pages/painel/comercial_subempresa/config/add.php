<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('comercial_subempresa', $acao);

$Painel->coluna(coluna: 2, callback: function () use ($Painel) {
    $Painel->fieldset('Dados da Subempresa', function () use ($Painel) {
        $Painel
            ->select(
                name: 'empresa->id',
                lista: 'empresa',
                label: 'Empresa Matriz',
                permissao: 'comercial_subempresa_empresa'
            )
            ->input(name: 'titulo', label: 'Titulo Interno', placeholder: 'Titulo Interno')
            ->input(name: 'razao_social', label: 'Razão Social', placeholder: 'Razão Social')
            ->input(name: 'nome_fantasia', label: 'Nome Fantasia', placeholder: 'Nome Fantasia')
            ->input(name: 'responsavel_nome', label: 'Nome do Responsável', placeholder: 'Nome do Responsável')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha uma status'),
                label: 'Status'
            );
    });
});

return $Painel;
