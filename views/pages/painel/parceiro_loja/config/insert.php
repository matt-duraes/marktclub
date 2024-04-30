<?php

use Helpers\ApiHelper;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;

$empresa = (new ApiHelper(token: true))
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];
$tag = (new ApiHelper(token: true))
    ->get('/parceiro-subcategoria/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add(app: 'parceiro_loja', acao: $acao);
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados básicos', function () use ($Painel) {
        $Categoria = new Categoria();
        $gerente = sessao('USUARIO')['gerente'] ?? '' == 'sim';
        if ($gerente) {
            $equipe = (new ApiHelper(token: true))
                ->get('/usuario-equipe/select')
                ->array()['dado'] ?? [];
            $Painel->select(
                name: 'equipe',
                label: 'Operador',
                placeholder: 'Escolha um operador',
                lista: $equipe
            );
        } else {
            $Painel->html('<input name="equipe" id="input_equipe" value="' . sessao('USUARIO.id') . '">', acao: 'add');
        }
        $Painel
            ->input(
                name: 'titulo_interno',
                label: 'Título',
                placeholder: 'Digite um título para o painel',
                contador: 100,
            )
            ->select(
                name: 'tipo_loja',
                label: 'Tipo de loja',
                placeholder: 'Escolha um tipo de loja',
                lista: (new TipoLoja())->select('Escolha uma opção')
            )
            ->select(
                name: 'categoria_principal',
                label: 'Categoria',
                placeholder: 'Categoria',
                lista: $Categoria->select('Escolha uma opção')
            )
            ->uri(name: 'url', label: 'URL do clube', placeholder: 'Url do clube')
            ->switch(name: 'convenio_direto', label: 'É um convênio direto?', acao: 'add');
    });

    $Painel->fieldset('Responsável', function () use ($Painel) {
        $Painel
            ->input(
                name: 'responsavel_nome',
                label: 'Nome do responsável',
                placeholder: 'Digite um nome',
                contador: 100,
            )
            ->input(
                name: 'responsavel_cargo',
                label: 'Cargo',
                placeholder: 'Digite um cargo',
            )
            ->cpf(
                name: 'responsavel_cpf',
                label: 'CPF',
                placeholder: 'Digite um CPF',
            )
            ->telefone(
                name: 'responsavel_telefone',
                label: 'Telefone',
                placeholder: 'Digite um telefone',
            )
            ->email(
                name: 'responsavel_email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail'
            );
    });
});
$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        todos: 'Marcar todas as empresas',
        mais: true,
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        }
    );
});

return $Painel;
