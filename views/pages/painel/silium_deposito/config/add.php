<?php

use App\Classes\SiliumDeposito\Status;
use PainelConfig\Add;

$Painel = new Add('silium_deposito', $acao);

$Status = new Status();
$Painel->coluna(callback: function () use ($Painel, $Status) {
    $listaStatus = $Status->select('Selecione um status');
    unset($listaStatus[Status::AGUARDANDO]);

    $Painel->fieldset('Informações do Depósito', function () use ($Painel, $listaStatus) {
        $Painel
            ->input(
                name: 'saque',
                label: 'Identificação da Solicitação',
                placeholder: 'Insira a identificação da solicitação (UUID)'
            )
            ->dinheiro(
                name: 'valor',
                label: 'Valor do Depósito',
                placeholder: 'Insira o valor que foi depositado (exceto em mensalidade)'
            )
            ->data(
                name: 'data_deposito',
                label: 'Data do Depósito',
                placeholder: 'Insira a data do depósito',
                obrigatorio: true
            )
            ->select(
                name: 'status',
                lista: $listaStatus,
                label: 'Status',
                placeholder: 'Status',
                obrigatorio: true
            );
    });

    $Painel->fieldset('Comprovante do Depósito', function () use ($Painel) {
        $Painel
            ->imagem(
                name: 'documento_anexo',
                diretorio: '2a957957-05be-4024-a5fb-7f69a4a0d07f',
                label: 'Imagem/Foto (exceto em mensalidade)'
            );
    });
});

return $Painel;
