<?php

use App\Classes\UsuarioIndicacao\Status;

$Painel = new PainelConfig\Visualizar('usuario_indicacao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Dados do usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->email('email', 'E-mail')
            ->telefone('telefone', 'Telefone');
    });

    $Painel->bloco('Usuário que indicou', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('quem_indicou', 'Usuário foi deletado e não existe mais.')
            ->linha('quem_indicou->nome', 'Nome')
            ->cpf('quem_indicou->cpf', 'CPF')
            ->email('quem_indicou->email', 'E-mail')
            ->botao('usuario_link', 'Ver usuário', link: LINK . '/app/visualizar/usuario-cliente/->quem_indicou->id');
    });

    $Painel->bloco('Usuário após ativar', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario_ativo', 'Usuário não ativou o cadastro ainda.')
            ->linha('usuario_ativo->nome', 'Nome')
            ->cpf('usuario_ativo->cpf', 'CPF')
            ->email('usuario_ativo->email', 'E-mail')
            ->botao('usuario_ativo_link', 'Ver usuário', link: LINK . '/app/visualizar/usuario-cliente/->usuario_ativo->id');
    });

    $Painel->bloco('Dados da indicação', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização')
            ->linha('status', 'Status');
    });

    $Painel->status(
        campo: 'status',
        texto: 'Bloquear usuário',
        inArray: ['Indicado'],
        status: Status::BLOQUEADO,
        mensagem: 'Deseja bloquear o usuário? Essa ação não pode ser desfeita.',
        cor: 'vermelho'
    );
});

$Painel->replace('status', (new Status())->select());

return $Painel;
