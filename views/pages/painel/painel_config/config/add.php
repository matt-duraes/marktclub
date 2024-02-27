<?php

use App\Helpers\Painel\ConfiguracoesPadrao;
use Helpers\ApiHelper;

$Painel = new PainelConfig\Add('painel_config', $acao);

$empresas = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma empresa'])
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$Painel->coluna(callback: function () use ($Painel, $empresas) {
    $Painel
        ->select(
            name: 'empresa',
            lista: $empresas,
            label: 'Escolha uma empresa',
            permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
        );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Recursos do Painel',
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'configuracao[]', label: 'Nome', value: 'nome');
            $Painel->checkbox(name: 'configuracao[]', label: 'Perfil', value: 'perfil');
            $Painel->checkbox(name: 'configuracao[]', label: 'Bloquear Tela', value: 'bloquear');
            $Painel->checkbox(name: 'configuracao[]', label: 'Agenda Google', value: 'agenda');
        },
        todos: 'Marcar todos os recursos',
        mais: 1
    );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Campos Obrigátorios de Usuário',
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'CPF', value: 'cpf');
            $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'E-mail', value: 'email');
            $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'Matricula', value: 'matricula');
            $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'SIAPE', value: 'siape');
            $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'Status', value: 'status');
        },
        todos: 'Marcar todos os campos',
        mais: 1
    );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Permissões',
        callback: function () use ($Painel) {
            foreach (ConfiguracoesPadrao::PERMISSOES as $ind => $dado) {
                $titulo = $dado['titulo'] ?? '';
                $Painel->html(html: '<input type="hidden" name="titulo[' . $ind . ']" value="' . $titulo . '">');
                if (!empty($titulo)) {
                    $Painel->html('<h3>' . $titulo . '</h3>');
                }
                if (array_key_exists('permissao', $dado)) {
                    foreach ($dado['permissao'] as $permissaoFinal => $nomePermissao) {
                        $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissaoFinal);
                    }
                }
            }
        },
        todos: 'Marcar todas as permissões',
        mais: 1
    );
});

return $Painel;
