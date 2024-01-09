<?php

use Helpers\ApiHelper;

$Painel = new PainelConfig\Add('painel_permissoes', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $empresas = (new ApiHelper(token: true))
        ->json(['titulo' => 'Escolha uma empresa'])
        ->get('/comercial-empresa/select')
        ->array();

    $Painel
        ->select(
            name: 'empresa',
            lista: $empresas['dado'] ?? [],
            label: 'Escolha uma empresa',
            permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
        );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Recursos do Painel',
        callback: function () use ($Painel) {
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
            $permissao = sessao('PAINEL.permissao.montar');
            foreach ($permissao as $ind => $dado) {
                $titulo = $dado['titulo'] ?? '';
                $Painel->html(html: '<input type="hidden" name="titulo[' . $ind . ']" value="' . $titulo . '">');
                if (!empty($titulo)) {
                    $Painel->html('<h4>' . $titulo . '</h4>');
                }
                if (array_key_exists('acao', $dado)) {
                    foreach ($dado['acao'] as $acao) {
                        if ($acao == 'index') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Listar', value: $ind . '_index');
                        } elseif ($acao == 'visualizar') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Visualizar', value: $ind . '_visualizar');
                        } elseif ($acao == 'download') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Download', value: $ind . '_download');
                        } elseif ($acao == 'add') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Salvar', value: $ind . '_add');
                        } elseif ($acao == 'editar') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Editar', value: $ind . '_editar');
                        } elseif ($acao == 'deletar') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Deletar', value: $ind . '_deletar');
                        } elseif ($acao == 'status') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Mudar status', value: $ind . '_status');
                        } elseif ($acao == 'empresa') {
                            $Painel->checkbox(
                                name: 'permissao[]',
                                label: 'Todas as empresas',
                                value: $ind . '_empresa'
                            );
                        } elseif ($acao == 'analytics') {
                            $Painel->checkbox(name: 'permissao[]', label: 'Analytics', value: $ind . '_analytics');
                        }
                    }
                } elseif (array_key_exists('permissao', $dado)) {
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
