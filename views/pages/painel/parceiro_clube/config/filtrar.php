<?php

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Classes\ParceiroLoja\TipoLoja;
use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use PainelConfig\Filtrar;

$Painel = new Filtrar('parceiro_loja');

$subcategoria = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/parceiro-subcategoria/select')
    ->array()['dado'] ?? [];

$Painel
    ->bloco(function () use ($Painel, $subcategoria) {
        $Painel
            ->select(
                name: 'categoria',
                lista: (new Categoria())->select('Escolha uma opção'),
                label: 'Categoria',
                placeholder: 'Escolha uma categoria'
            )
            ->select(
                name: 'subcategoria',
                lista: $subcategoria,
                label: 'Subcategoria',
                placeholder: 'Escolha uma subcategoria'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'tipo_estabelecimento',
                lista: (new TipoEstabelecimento())->select(
                    'Escolha uma opção'
                ),
                label: 'Estabelecimento'
            )
            /*->select(
                name: 'tipo_loja',
                lista: array_merge(
                    (new TipoLoja())->select('Escolha uma opção'),
                    ['desconto' => 'Desconto']
                ),
                label: 'Tipo de loja'
            )*/;
    })
    ->select(
        name: 'convenio_direto',
        lista: ['' => 'Buscar todos', 'sim' => 'Apenas convênio direto', 'nao' => 'Sem ser convênio direto'],
        label: 'Convênio direto'
    )
    ->bloco(
        callback: function () use ($Painel) {
            foreach ((new EnderecoEstado())->select() as $ind => $val) {
                $Painel->checkbox(name: 'endereco_estado[]', label: $val, value: $ind);
            }
        },
        coluna: 3,
        titulo: 'Endereço',
        mais: true,
        todos: 'Marcar todos'
    );

$Painel->replace('tipo_estabelecimento', (new TipoEstabelecimento())->select());
$Painel->replace('tipo_loja', (new TipoLoja())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
