<?php

use PainelConfig\Visualizar;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoResgate;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_saque');

$TipoConta = new TipoConta();
$Status = new Status();
$TipoResgate = new TipoResgate();

$Painel->coluna(callback: function () use ($Painel, $Status) {
    $Painel->bloco('Identificação da Solicitação', function () use ($Painel) {
        $Painel
            ->linha('id', 'UUID');
    });

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

    $Painel->bloco('Dados da Solicitação', function () use ($Painel) {
        $Painel
            ->linha('tipo_resgate', 'Tipo de Resgate')
            ->linha('pontuacao', 'Pontuação')
            ->dinheiro('valor', 'Valor');
    });

    $Painel->bloco('Dados Bancários', function () use ($Painel) {
        $Painel
            ->linha('nome_titular', 'Nome do Titular')
            ->cpf('documento_cpf', 'CPF do Titular')
            ->linha('tipo_conta', 'Tipo de Conta')
            ->linha('banco', 'Instituição Financeira')
            ->linha('agencia', 'Agência')
            ->linha('conta', 'Conta');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });
});

$Painel->replace('tipo_conta', $TipoConta->select());
$Painel->replace('status', $Status->select());
$Painel->replace('tipo_resgate', $TipoResgate->select());

return $Painel;
