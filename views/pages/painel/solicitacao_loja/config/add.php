<?php

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\UsuarioEquipe\Tipo;
use Helpers\ApiHelper;
use PainelConfig\Add;

$Painel = new Add(app: 'solicitacao_loja', acao: $acao);

$empresa = (new ApiHelper(token: true))->get('/comercial-empresa/select')->array()['dado'] ?? [];
$parceiro = (new ApiHelper(token: true))
    ->json([
        'titulo'    => 'Escolha um parceiro',
        'tipo_loja' => TipoLoja::LOJA,
        'status'    => [Status::PROSPECCAO, Status::CONCLUIDO, Status::CANCELADO]
    ])
    ->get('/parceiro-loja/select')
    ->array();

$Painel->coluna(callback: function () use ($Painel, $parceiro, $empresa) {
    $Painel->fieldset('Víncular Parceiro', function () use ($Painel, $parceiro) {
        $Painel
            ->select(
                name: 'parceiro',
                lista: empty($parceiro['dado']) ? ['' => 'Nenhum parceiro encontrado'] : $parceiro['dado'],
                label: 'Parceiro'
            );
    });
    $Painel->fieldset('Prospectar e vincular parceiro', function () use ($Painel, $empresa) {
        $Painel
            ->input(
                name: 'parceiro_novo',
                label: 'Informe o nome do parceiro',
                placeholder: 'Nome do parceiro',
                ajuda: 'Informe o nome do parceiro que deseja adicionar e vincular a esta indicação'
            )
            ->select(
                name: 'gestor',
                lista: 'usuario',
                label: 'Responsável',
                tipoEquipe: Tipo::CONVENIO
            )
            ->blocoCheckbox(
                titulo: 'Empresas',
                callback: function () use ($Painel, $empresa) {
                    foreach ($empresa as $id => $nome) {
                        $Painel->checkbox(name: 'empresas[]', label: $nome ?? '', value: $id);
                    }
                },
                todos: 'Marcar todas as empresas',
                mais: true
            );
    });
});

return $Painel;
