<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Tipo;

$Loja = (new ApiHelper(token: true))
    ->json([
        'titulo' => 'Escolha um parceiro',
        'tipo'   => Tipo::AUTOMOVEL
    ])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add('parceiro_automovel');

$Painel->coluna(callback: function () use ($Painel, $Loja) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem('imagem', '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Dados', function () use ($Painel, $Loja) {
        $Painel
            ->input(name: 'titulo', label: 'Modelo')
            ->select(name: 'parceiro->id', label: 'Parceiro', lista: $Loja)
            ->data(name: 'data_inicio', label: 'Publicar em', separador: 'até', placeholder: 'Publicar em')
            ->data(name: 'data_final', label: 'Remover em', separador: 'até', placeholder: 'Remover em')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));
    });
});
return $Painel;
