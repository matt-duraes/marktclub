<?php

use App\Classes\ParceiroLoja\Categoria;

$Painel = new PainelConfig\Add(app: 'parceiro_externo', acao: $acao);
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados básicos', function () use ($Painel) {
        $Categoria = new Categoria();
        $Painel
            ->input(
                name: 'titulo_interno',
                label: 'Título',
                placeholder: 'Digite o nome para a parceria',
                contador: 50,
                obrigatorio: true
            )
            ->select(
                name: 'categoria_principal',
                label: 'Categoria',
                placeholder: 'Categoria',
                lista: $Categoria->select('Escolha uma opção'),
                obrigatorio: true
            );
    });

    $Painel->fieldset('Contato', function () use ($Painel) {
        $Painel
            ->input(
                name: 'nome',
                label: 'Nome para contato',
                placeholder: 'Digite um nome',
                contador: 100,
                obrigatorio: true
            )
            ->telefone(
                name: 'telefone',
                label: 'Telefone',
                placeholder: 'Digite um telefone',
                obrigatorio: true
            )
            ->email(
                name: 'email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail',
                obrigatorio: true
            );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->endereco('Endereço');
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Mensagem', function () use ($Painel) {
        $Painel
            ->textarea(
                name: 'mensagem',
                label: 'Mensagem',
                placeholder: 'Digite sua mensagem',
                obrigatorio: true
            );
    });
});

$Painel->css('painel_parceiro_externo_add');
$Painel->js('painel_parceiro_externo_add');
return $Painel;
