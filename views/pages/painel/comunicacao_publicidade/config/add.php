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

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Informações de Identificação', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título Interno', placeholder: 'Digite um título interno');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
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
    $Painel->fieldset('Informações do(s) banner(s)', function () use ($Painel, $parceiro) {
        $Painel
            ->select(name: 'tipo', lista: (new Tipo())->select('Escolha um tipo'), label: 'Tipo', placeholder: 'Escolha o local do banner')
            ->select(name: 'parceiro->id', lista: $parceiro, label: 'Parceiro', placeholder: 'Escolha o parceiro do banner')
            ->url(name: 'link', label: 'Link', placeholder: 'Caso haja link externo, informe aqui');
    });
    $Painel->fieldset('Informações da publicação', function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', label: 'Publicar em', placeholder: 'Publicar em', separador: 'até')
            ->data(name: 'data_final', label: 'Remover em', placeholder: 'Remover em', separador: 'até')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status',
                placeholder: 'Status'
            );
    });
});

return $Painel;
