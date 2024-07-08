<?php

use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\UsuarioCliente\Helper;
use PainelConfig\Visualizar;

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
            ->linha('nome_titular', 'Nome do Titular')
            ->cpf('documento_cpf', 'CPF do Titular')
            ->linha('tipo_conta', 'Tipo de Conta')
            ->linha('banco', 'Instituição Financeira')
            ->linha('agencia', 'Agência')
            ->linha('conta', 'Conta');
    });

    $Painel->bloco('Dados do depósito', function () use ($Painel) {
        $Painel
            ->linha('pontuacao', 'Pontuação Resgatada')
            ->dinheiro('valor', 'Valor de Resgate')
            ->data('data_deposito', 'Data de Depósito')
            ->imagemLogo('documento_anexo');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });
});

$Painel->replace('tipo_conta', (new TipoConta())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
