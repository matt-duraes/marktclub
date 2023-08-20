<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ComunicacaoPublicidade\Tipo;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$parceiro = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um parceiro'])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'comunicacao_publicidade', acao: $acao);
$Painel->coluna(callback: function () use ($Painel, $parceiro) {
    $Painel->fieldset('Imagem desktop', function () use ($Painel) {
        $Painel
            ->imagem('imagem_desktop', 'd55fc9dc-e2c0-4294-b50d-d6730d234521');
    });
    $Painel->fieldset('Imagem mobile', function () use ($Painel) {
        $Painel
            ->imagem('imagem_mobile', 'd55fc9dc-e2c0-4294-b50d-d6730d234521');
    });
});
$Painel->coluna(callback: function () use ($Painel, $parceiro) {
    $Painel->fieldset('Dados pricipais', function () use ($Painel, $parceiro) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->data(name: 'data_inicio', label: 'Publicar em', separador: 'até', placeholder: 'Publicar em')
            ->data(name: 'data_final', label: 'Remover em', separador: 'até', placeholder: 'Remover em')
            ->url(name: 'link', label: 'Link', placeholder: 'Link externo');
    });
    $Painel->fieldset('Dados secundários', function () use ($Painel, $parceiro) {
        $Painel
            ->select(name: 'tipo', label: 'Tipo', lista: (new Tipo())->select('Escolha um tipo'))
            ->select(name: 'parceiro->id', label: 'Parceiro', lista: $parceiro)
            ->select(name: 'status', label: 'Status', placeholder: 'Status', lista: (new Status())->select('Escolha um status'));
    });
});

return $Painel;
