<?php

use PainelConfig\Visualizar;
use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\TipoConta;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_deposito');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Usuario', function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Dados Bancários', function () use ($Painel) {
        $Painel
            ->linha('saque->nome_titular', 'Nome do Titular')
            ->cpf('saque->documento_cpf', 'CPF do Titular')
            ->linha('saque->tipo_conta', 'Tipo de Conta')
            ->linha('saque->banco', 'Instituição Financeira')
            ->linha('saque->agencia', 'Agência')
            ->linha('saque->conta', 'Conta');
    });

    $Painel->bloco('Dados do depósito', function () use ($Painel) {
        $Painel
            ->linha('saque->pontuacao', 'Pontuação Resgatada')
            ->dinheiro('valor', 'Valor de Resgate')
            ->data('data_deposito', 'Data de Depósito')
            ->linha('documento_anexo', 'Comprovante de Depósito');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    /*$Painel
        ->status(
            campo: 'status',
            texto: 'Finalizar solicitação',
            inArray: ['Novo', 'Enviado p/ Empresa', 'Enviado p/ Usuário', 'Problema'],
            status: Status::FINALIZADO,
            mensagem: 'Tem certeza que deseja fechar essa solicitação?',
            cor: 'verde'
        );*/
});

$Painel->replace('tipo_conta', (new TipoConta())->select());
$Painel->replace('status', (new StatusDeposito())->select());

return $Painel;
