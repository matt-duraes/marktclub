<?php

use PainelConfig\Visualizar;
use App\Classes\Silium\StatusSaque;
use App\Classes\Silium\TipoConta;
use App\Classes\UsuarioCliente\Helper;

$Painel = new Visualizar('silium_saque');

$StatusSaque = new StatusSaque();
$Painel->coluna(callback: function () use ($Painel, $StatusSaque) {
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
            ->linha('nome_titular', 'Nome do Titular')
            ->cpf('documento_cpf', 'CPF do Titular')
            ->linha('tipo_conta', 'Tipo de Conta')
            ->linha('banco', 'Instituição Financeira')
            ->linha('agencia', 'Agência')
            ->linha('conta', 'Conta')
            ->linha('pontuacao', 'Pontuação');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Deposito realizado',
            inArray: [$StatusSaque->nome(StatusSaque::AGUARDANDO)],
            status: StatusSaque::DEPOSITADO,
            mensagem: 'Tem certeza que deseja alterar o status para depositado?',
            cor: 'verde'
        )->status(
            campo: 'status',
            texto: 'Deposito negado',
            inArray: [$StatusSaque->nome(StatusSaque::AGUARDANDO)],
            status: StatusSaque::NEGADO,
            mensagem: 'Tem certeza que deseja alterar o status para negado?',
            cor: 'vermelho'
        );
});

$Painel->replace('tipo_conta', (new TipoConta())->select());
$Painel->replace('status', $StatusSaque->select());

return $Painel;
