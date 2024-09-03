<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add('parceiro_campanha');

$parceiro = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um parceiro'])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel->coluna(callback: function () use ($Painel, $parceiro) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem(name: 'imagem_desktop', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c', label: 'Desktop');
        $Painel->imagem(name: 'imagem_mobile', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c', label: 'Mobile');
    });
    $Painel->fieldset('Dados', function () use ($Painel, $parceiro) {
        $Painel
            ->select(name: 'parceiro', lista: $parceiro, label: 'Parceiro', placeholder: 'Escolha o parceiro do banner', obrigatorio: true)
            ->input(name: 'titulo', label: 'Título', contador: 30, obrigatorio: true)
            ->input(name: 'texto', label: 'Texto', contador: 192, obrigatorio: true)
            ->url(name: 'link', label: 'Link', placeholder: 'Digite o link pra o usuário', obrigatorio: true)
            ->data(name: 'data_inicio', label: 'Publicar em', placeholder: 'Publicar em', separador: 'até', obrigatorio: true)
            ->data(name: 'data_final', label: 'Remover em', placeholder: 'Remover em', separador: 'até')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'), obrigatorio: true);
    });
});

return $Painel;
