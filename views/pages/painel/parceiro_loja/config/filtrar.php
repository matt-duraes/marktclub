<?php

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Helper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Classes\ParceiroLoja\TipoLoja;
use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use PainelConfig\Filtrar;

$Painel = new Filtrar('parceiro_loja');

$empresas = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/usuario-equipe/select')
    ->array()['dado'] ?? [];

$subcategoria = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma opção'])
    ->get('/parceiro-subcategoria/select')
    ->array()['dado'] ?? [];

$Painel
    ->input(name: 'titulo', label: 'Título', placeholder: 'Digite um título')
    ->bloco(function () use ($Painel, $equipe, $empresas) {
        $Painel
            ->select(
                name: 'equipe',
                lista: $equipe,
                label: 'Equipe'
            );
    })
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
            ->select(
                name: 'tipo_loja',
                lista: array_merge(
                    (new TipoLoja())->select('Escolha uma opção'),
                    ['desconto' => 'Desconto']
                ),
                label: 'Tipo de loja'
            );
    })
    ->data(
        name: ['data_criacao_de', 'data_criacao_ate'],
        label: 'Data de criação',
        placeholder: ['Data de criação', 'Data de criação'],
        separador: 'até'
    )
    ->data(
        name: ['data_publicacao_de', 'data_publicacao_ate'],
        label: 'Data de publicação',
        placeholder: ['Data de publicação', 'Data de publicação'],
        separador: 'até'
    )
    ->data(name: ['data_prospeccao_de', 'data_prospeccao_ate'],
        label: 'Data de prospecção',
        placeholder: ['Data de prospecção', 'Data de prospecção'],
        separador: 'até')
    ->data(
        name: ['data_problema_de', 'data_problema_ate'],
        label: 'Data do problema',
        placeholder: ['Data do problema', 'Data do problema'],
        separador: 'até'
    )
    ->data(
        name: ['data_cancelado_de', 'data_cancelado_ate'],
        label: 'Data de cancelamento',
        placeholder: ['Data de cancelamento', 'Data de cancelamento'],
        separador: 'até'
    )
    ->data(
        name: ['data_auditoria_de', 'data_auditoria_ate'],
        label: 'Data de auditoria',
        placeholder: ['Data de auditoria', 'Data de auditoria'],
        separador: 'até'
    )
    ->select(
        name: 'convenio_direto',
        lista: ['' => 'Buscar todos', 'sim' => 'Apenas convênio direto', 'nao' => 'Sem ser convênio direto'],
        label: 'Convênio direto'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha uma opção'),
        label: 'Status'
    )
    ->bloco(
        function () use ($Painel, $empresas) {
            foreach ($empresas as $id => $nome) {
                $Painel->checkbox(
                    name: 'empresas[]',
                    label: $nome,
                    value: $id,
                    permissao: Helper::PERMISSAO_EMPRESA
                );
            }
        },
        2,
        'Empresas',
        true,
        'Marcar todos'
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
    ->replace('tipo_estabelecimento', (new TipoEstabelecimento())->select())
    ->replace('tipo_loja', (new TipoLoja())->select())
    ->replace('status', (new Status())->select());

return $Painel;
