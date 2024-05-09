<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\TipoLoja;

$Loja = (new ApiHelper(token: true))
    ->validar('Erro ao buscar parceiros')
    ->json([
        'titulo'      => 'Escolha um parceiro',
        'tipo_loja'   => TipoLoja::AUTOMOVEL
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
            ->select(name: 'parceiro->id', lista: $Loja, label: 'Parceiro')
            ->data(name: 'data_inicio', label: 'Publicar em', placeholder: 'Publicar em', separador: 'até')
            ->data(name: 'data_final', label: 'Remover em', placeholder: 'Remover em', separador: 'até')
            ->select(name: 'status', lista: (new Status())->select('Escolha uma opção'), label: 'Status');
    });
});
return $Painel;
