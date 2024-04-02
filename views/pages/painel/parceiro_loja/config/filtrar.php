<?php

use Helpers\ApiHelper;
use App\Classes\ParceiroLoja\Helper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\TipoEstabelecimento;

$Painel = new PainelConfig\Filtrar('parceiro_loja');

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/usuario-equipe/select')
    ->array()['dado'] ?? [];
$empresa = [];
$Painel
    ->input(name: 'pesquisa', label: 'Pesquisa', placeholder: 'Digite uma pesquisa')
    ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->select(name: 'equipe', label: 'Equipe', lista: $equipe)
    ->select(name: 'tipo_estabelecimento', label: 'Estabelecimento', lista: (new TipoEstabelecimento())->select('Escolha uma opção'))
    ->select(name: 'tipo_loja', label: 'Tipo de loja', lista: (new TipoLoja())->select('Escolha uma opção'))
    ->select(
        name: 'status',
        label: 'Status',
        lista: (new Status())->select('Escolha uma opção')
    );

$Painel
    ->replace('equipe', $equipe)
    ->replace('empresa', $empresa)
    ->replace('tipo_estabelecimento', (new TipoEstabelecimento())->select())
    ->replace('tipo_loja', (new TipoLoja())->select())
    ->replace('status', (new Status())->select());

return $Painel;
