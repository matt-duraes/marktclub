<?php

use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/usuario-equipe/select')
    ->array()['dado'] ?? [];

$Painel
    ->input(name: 'pesquisa', label: 'Pesquisa', placeholder: 'Faça uma pesquisa')
    ->bloco(function () use ($Painel, $equipe) {
        if (temPermissao('parceiro_externo_equipe')) {
            $Painel->select(name: 'equipe', label: 'Equipe', lista: $equipe);
        }
        $Painel->select(name: 'categoria', label: 'Categoria', placeholder: 'Escolha uma categoria', lista: (new Categoria())->select('Escolha uma opção'));
    })
    ->data(name: ['data_criacao_de', 'data_criacao_ate'], label: 'Data de criação', placeholder: ['Data de criação', 'Data de criação'], separador: 'até')
    ->select(
        name: 'status',
        label: 'Status',
        lista: [
            Status::PROSPECCAO    => 'Prospecção',
            Status::CONCLUIDO     => 'Concluido',
            Status::SEM_INTERESSE => 'Sem interesse'
        ]
    )
    ->bloco(
        coluna: 2,
        titulo: 'Endereço',
        mais: true,
        todos: 'Marcar todos',
        callback: function () use ($Painel) {
            foreach ((new EnderecoEstado())->select() as $ind => $val) {
                $Painel->checkbox(name: 'endereco_estado[]', label: $val, value: $ind);
            }
        }
    );

$Painel
    ->replace('equipe', $equipe)
    ->replace('status', (new Status())->select());

return $Painel;
