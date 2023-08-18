<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$parceiro = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um parceiro'])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'comunicacao_historico', acao: $acao);
$Painel->coluna(callback: function () use ($Painel, $parceiro) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem('imagem', 'd55fc9dc-e2c0-4294-b50d-d6730d234521');
    });
    $Painel->fieldset('Dados da publicacao', function () use ($Painel, $parceiro) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->select(name: 'parceiro', label: 'Parceiro', lista: $parceiro)
            ->data(name: 'data_inicio', label: 'Publicar em', separador: 'até', placeholder: 'Publicar em')
            ->data(name: 'data_final', label: 'Remover em', separador: 'até', placeholder: 'Remover em')
            ->select(name: 'status', label: 'Status', placeholder: 'Status', lista: (new Status())->select('Escolha um status'));
    });
});
$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        todos: 'Marcar todas as empresas',
        mais: true,
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        }
    );
});

return $Painel;
