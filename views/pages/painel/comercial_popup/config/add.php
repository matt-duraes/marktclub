<?php

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use Helpers\ApiHelper;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'comercial_popup', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados gerais', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo_painel', label: 'Título Interno');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados Principais', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->data(name: 'data_inicio', label: 'Data de Início')
            ->data(name: 'data_final', label: 'Data Final')
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                label: 'Status'
            )
            ->select(
                name: 'uri',
                lista: [
                    ''                    => 'Todas',
                    '/convenios'          => 'Convenios',
                    '/samsung'            => 'Samsung',
                    '/farmacia'           => 'Farmácia',
                    '/cupom'              => 'Cupom',
                    '/cashback'           => 'Cashback',
                    '/automoveis'         => 'Automóveis',
                    '/cinema'             => 'Cinema',
                    '/corrida'            => 'Corrida',
                    '/show-nacional'      => 'Show Nacional',
                    '/show-internacional' => 'Show Internacional',
                    '/credito'            => 'Crédito',
                    '/plano-odontologico' => 'Plano Odontológico',
                    '/saude'              => 'Saúde',
                ],
                label: 'Página'
            );
    });

    $Painel->fieldset('Dados do Link', function () use ($Painel) {
        $Painel
            ->input(name: 'botao_texto', label: 'Texto do Botão')
            ->url(name: 'botao_link', label: 'Link do Botão')
            ->select(
                name: 'botao_target',
                lista: (new BotaoTarget())->select('Escolha um tipo'),
                label: 'Tipo de Link'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Tipos de Usuário',
        callback: function () use ($Painel) {
            foreach ((new TipoUsuario())->select() as $key => $nome) {
                if ($key == 'super') {
                    continue;
                }

                $Painel->checkbox(name: 'usuario_tipo[]', label: $nome, value: $key);
            }
        },
        todos: 'Marcar todos os tipos',
        mais: false
    );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Conteúdo e Mídia', function () use ($Painel) {
        $Painel
            ->imagem('imagem', '00a9c7ca-7dd9-43a1-9dc7-20297a26d49d')
            ->editor(name: 'texto', label: 'Texto')
            ->editor(name: 'regulamento', label: 'Regulamento');
    });
});

$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome ?? 'sem nome fantasia', value: $id);
            }
        },
        todos: 'Marcar todas as empresas',
        mais: true
    );
});

return $Painel;
