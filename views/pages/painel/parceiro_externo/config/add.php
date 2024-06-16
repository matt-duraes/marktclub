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
                contador: 80,
            )
            ->select(
                name: 'categoria_principal',
                label: 'Categoria',
                placeholder: 'Categoria',
                lista: $Categoria->select('Escolha uma opção')
            );
    });

    $Painel->fieldset('Contato', function () use ($Painel) {
        $Painel
            ->input(
                name: 'nome',
                label: 'Nome para contato',
                placeholder: 'Digite um nome',
                contador: 100,
            )
            ->telefone(
                name: 'telefone',
                label: 'Telefone',
                placeholder: 'Digite um telefone',
            )
            ->email(
                name: 'email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail',
            )
        ;
    });
});

return $Painel;
