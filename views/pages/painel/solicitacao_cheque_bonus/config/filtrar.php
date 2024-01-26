<?php

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoChequeBonus\Helper;
use App\Classes\UsuarioCliente\TipoUsuario;

$Painel = new PainelConfig\Filtrar('solicitacao_cheque_bonus');

$Painel
    ->input(
        name: 'nome',
        titulo: 'Nome usuário',
        label: 'Nome usuário',
        placeholder: 'Nome usuário'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'empresa',
                lista: 'empresa',
                titulo: 'Empresa',
                label: 'Empresa',
                placeholder: 'Empresa',
                permissao: Helper::PERMISSAO_EMPRESA
            )
            ->select(
                name: 'tipo_usuario',
                lista: (new TipoUsuario())->select('Escolha um tipo'),
                titulo: 'Tipo usuário',
                label: 'Tipo usuário',
                placeholder: 'Tipo usuário'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Solicitado de',
                label: 'Solicitado de',
                placeholder: 'Solicitado de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Solicitado até',
                label: 'Solicitado até',
                placeholder: 'Solicitado até'
            );
    })
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
