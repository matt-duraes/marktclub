<?php

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use Helpers\ApiHelper;
use Modules\EnderecoEstado;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/usuario-equipe/select')
    ->array()['dado'] ?? [];

$Painel
    ->input(
        name: 'pesquisa',
        label: 'Pesquisa',
        placeholder: 'Faça uma pesquisa'
    )
    ->select(
        name: 'empresa',
        lista: 'empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: 'parceiro_externo_equipe'
    )
    ->bloco(function () use ($Painel, $equipe) {
        $Painel
            ->select(
                name: 'id_usuario_equipe',
                lista: $equipe,
                label: 'Equipe',
                placeholder: 'Equipe',
                permissao: 'parceiro_externo_equipe'
            )
            ->select(
                name: 'categoria_principal',
                lista: (new Categoria())->select('Escolha uma opção'),
                label: 'Categoria',
                placeholder: 'Escolha uma categoria'
            );
    })
    ->data(
        name: ['data_criacao_de', 'data_criacao_ate'],
        label: 'Data de criação',
        placeholder: ['Data de criação', 'Data de criação'],
        separador: 'até'
    )
    ->select(
        name: 'status',
        lista: [
            Status::PROSPECCAO    => 'Prospecção',
            Status::CONCLUIDO     => 'Concluido',
            Status::SEM_INTERESSE => 'Sem interesse'
        ],
        label: 'Status',
        placeholder: 'Status'
    )
    ->select(
        name: 'tipo_indicador',
        lista: (new Indicador())->select('Escolha uma opção'),
        label: 'Indicador',
        placeholder: 'Indicador'
    )
    ->bloco(
        callback: function () use ($Painel) {
            foreach ((new EnderecoEstado())->select() as $ind => $val) {
                $Painel->checkbox(name: 'endereco_estado[]', label: $val, value: $ind);
            }
        },
        coluna: 2,
        titulo: 'Endereço',
        mais: true,
        todos: 'Marcar todos'
    );

$Painel
    ->replace('equipe', $equipe)
    ->replace('status', (new Status())->select());

return $Painel;
