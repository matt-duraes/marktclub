<?php

use App\Classes\ComercialSubempresa\Status;

$Painel = new PainelConfig\Add('comercial_subempresa', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados da Subempresa', function () use ($Painel) {
        $Painel
            ->input(name: 'nome', label: 'Nome', placeholder: 'Nome')
            ->cnpj(name: 'documento_cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->select(
                name: 'empresa',
                lista: 'empresa',
                label: 'Empresa'
            )
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status'
            );
    });
});

return $Painel;
