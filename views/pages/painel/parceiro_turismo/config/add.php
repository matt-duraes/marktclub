<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('parceiro_turismo');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem('imagem', '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título', contador: 30)
            ->input(name: 'texto', label: 'Texto', contador: 192)
            ->data(name: 'data_inicio', label: 'Publicar em', placeholder: 'Publicar em', separador: 'até')
            ->data(name: 'data_final', label: 'Remover em', placeholder: 'Remover em', separador: 'até')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));
    });
});

return $Painel;
