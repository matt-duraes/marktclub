<?php

use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use App\Classes\ParceiroLoja\Helper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\TipoEstabelecimento;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/usuario-equipe/select')
    ->array()['dado'] ?? [];

$Painel
    ->input(name: 'pesquisa', label: 'Pesquisa', placeholder: 'Digite uma pesquisa')
    ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->select(name: 'equipe', label: 'Equipe', lista: $equipe)
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(name: 'categoria', label: 'Categoria', placeholder: 'Escolha uma categoria', lista: (new Categoria())->select('Escolha uma opção'))
            ->select(name: 'subcategoria', label: 'Subcategoria', placeholder: 'Escolha uma subcategoria', lista: (new Categoria())->select('Escolha uma opção'));
    })
    ->select(name: 'tipo_estabelecimento', label: 'Estabelecimento', lista: (new TipoEstabelecimento())->select('Escolha uma opção'))
    ->select(name: 'tipo_loja', label: 'Tipo de loja', lista: (new TipoLoja())->select('Escolha uma opção'))
    ->select(
        name: 'status',
        label: 'Status',
        lista: (new Status())->select('Escolha uma opção')
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
    ->replace('tipo_estabelecimento', (new TipoEstabelecimento())->select())
    ->replace('tipo_loja', (new TipoLoja())->select())
    ->replace('status', (new Status())->select());

return $Painel;
