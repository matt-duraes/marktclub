<?php

use App\Classes\ComunicacaoPopup\Status;

$Painel = new PainelConfig\Filtrar('comunicacao_popup');

$Painel
    ->input(name: 'titulo', titulo: 'Título', label: 'Título', placeholder: 'Título')
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', titulo: 'Data Início', label: 'Data de início', placeholder: 'Data de início')
            ->data(name: 'data_final', titulo: 'Data Final', label: 'Data final', placeholder: 'Data final');
    })
    ->select(name: 'status', lista: (new Status())->select('Escolha um status'), titulo: 'Status', label: 'Status');

return $Painel;
