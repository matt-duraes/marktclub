<?php

use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Helper;
use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\TipoUsuario;

$Status = new Status();

$Painel = new PainelConfig\Filtrar('solicitacao_voucher');

$Painel
    ->select(name: 'empresa', lista: 'empresa', label: 'Empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'tipo',
                lista: (new Tipo())->select('Escolha uma opção'),
                label: 'Típo de voucher'
            )
            ->select(
                name: 'tipo_usuario',
                lista: (new TipoUsuario())->select('Escolha uma opção'),
                label: 'Tipo de usuário'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Criado em', label: 'Criado em')
            ->data(name: 'data_criacao_ate', titulo: 'Criado até', label: 'Criado até');
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_validacao_de', titulo: 'Validado em', label: 'Validado em')
            ->data(name: 'data_validacao_ate', titulo: 'Validado até', label: 'Validado até');
    })
    ->select(
        name: 'status',
        lista: $Status->select('Escolha uma opção'),
        titulo: 'Status',
        label: 'Status'
    );

$Painel->replace('status', $Status->select());

return $Painel;
