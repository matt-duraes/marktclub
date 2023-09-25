<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroCashback\Categoria;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add('parceiro_cashback');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Logo', function () use ($Painel) {
        $Painel->imagem('imagem', '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->dinheiro(name: 'comissao_minima', label: 'Comissão minima')
            ->dinheiro(name: 'comissao_maxima', label: 'Comissão maxima')
            ->url(name: 'link_site', label: 'Link', placeholder: 'Digite um link')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Descrições', function () use ($Painel) {
        $Painel
            ->textarea('texto_descricao', label: 'Descrição', placeholder: 'Digite uma descrição')
            ->textarea('texto_restricao', label: 'Restrições do parceiro', placeholder: 'Digite uma restrição')
            ->textarea('texto_outro', label: 'Outros dados', placeholder: 'Outros dados')
            ->select(name: 'categoria', label: 'Categoria', lista: (new Categoria())->select('Escolha uma opção'));
    });
});
$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        todos: 'Marcar todas as empresas',
        mais: true,
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome ?? 'sem nome fantasia', value: $id);
            }
        }
    );
});
return $Painel;
