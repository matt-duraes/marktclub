<?php

$Painel = new PainelConfig\Visualizar('usuario_indicacao');
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Dados do usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->linha('email', 'E-mail')
            ->linha('telefone', 'Telefone')
            ->linha('status', 'Status');
    });
    $Painel->bloco('Data', callback: function () use ($Painel) {
        $Painel
            ->linha('data_criacao', 'Data de criação')
            ->linha('data_atualizacao', 'Data de atualização');
    });
    $Painel->bloco('Usuário que indicou', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('quem_indicou', 'Usuário foi deletado e não existe mais.')
            ->linha('quem_indicou->nome', 'Nome')
            ->linha('quem_indicou->cpf', 'CPF')
            ->linha('quem_indicou->email', 'E-mail');
    });
    $Painel->bloco('Usuário após ativar', callback: function () use ($Painel) {
        $Painel
            ->vazioBreak('usuario', 'Usuário não ativou o cadastro ainda.')
            ->linha('usuario->nome', 'Nome')
            ->linha('usuario->cpf', 'CPF')
            ->linha('usuario->email', 'E-mail');
    });

    $Painel->status(
        campo: 'status',
        texto: 'Bloquear usuário',
        inArray: ['indicado'],
        status: 'bloqueado',
        mensagem: 'Deseja bloquear o usuário? Essa ação não pode ser desfeita.',
        cor: 'vermelho'
    );
});

$Painel->replace('status', [
    'indicado' => 'Indicado',
    'ativado' => 'Ativado',
    'bloqueado' => 'Bloqueado'
]);

return $Painel;
