<?php

use App\Classes\Silium\StatusDeposito;
use PainelConfig\Add;

$Painel = new Add('silium_deposito', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Informações do Depósito', function () use ($Painel) {
        $Painel
            ->select(
                name: 'saque',
                lista: ['' => 'Selecione a solicitação de saque'],
                label: 'Solicitação de Saque',
                placeholder: 'Digite o nome do usuário ou sua identificação (ID)',
                acao: 'add',
                obrigatorio: true
            )
            /*->input(
                name: 'saque',
                label: 'Solicitação de Saque',
                placeholder: 'Digite a identificação (ID)',
                acao: 'add'
            )*/
            ->dinheiro(
                name: 'valor',
                label: 'Valor do Depósito',
                placeholder: 'Insira o valor que foi depositado',
                obrigatorio: true
            )
            ->data(
                name: 'data_deposito',
                label: 'Data do Depósito',
                placeholder: 'Insira a data do depósito',
                obrigatorio: true
            )
            /*->select(
                name: 'status',
                lista: (new StatusDeposito())->select('Selecione um status'),
                label: 'Status',
                placeholder: 'Status',
                obrigatorio: true
            )*/
            ->imagem(
                name: 'documento_anexo',
                diretorio: '2d978fba-4bd2-4af7-80bf-ebb94d9ac991'
            );
    });
});
$Painel->js('painel_silium_deposito_add');

return $Painel;
