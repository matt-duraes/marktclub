<?php

use App\Classes\Geral\Status;
use Helpers\ApiHelper;

$Painel = new PainelConfig\Add(app: 'carteirinha', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $equipe = (new ApiHelper(token: true))
            ->json(['titulo' => 'Escolha uma empresa'])
            ->get('/comercial-empresa/select')
            ->array();

        $Painel
            ->select(
                name: 'empresa',
                lista: $equipe['dado'] ?? [],
                label: 'Escolha uma empresa',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
            )
            ->input(name: 'titulo', label: 'Título da carteirinha')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status',
                placeholder: 'Escolha um status'
            );
    });

    $Painel->fieldset('Imagem da frente', function () use ($Painel) {
        $Painel->imagem(name: 'bg_frente', diretorio: '3828fc5c-51cf-44b1-b8f0-6f8d1d3def19');
    });

    $Painel->fieldset('Quais informações vai possuir?', function () use ($Painel) {
        $Painel
            ->switch(name: 'nome', label: 'Vai ter nome?')
            ->switch(name: 'cpf', label: 'Vai ter CPF?')
            ->switch(name: 'matricula', label: 'Vai ter matricula?')
            ->switch(name: 'data_nascimento', label: 'Vai ter data nascimento?')
            ->switch(name: 'estado', label: 'Vai ter estado?');
    });
});

return $Painel;
