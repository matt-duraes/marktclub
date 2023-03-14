<?php

use App\Classes\UsuarioEquipe\Helper;
use App\Classes\UsuarioEquipe\Status;

$Painel = new \PainelConfig\Add('usuario_equipe', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', callback: function () use ($Painel) {
        $Painel->input(name: 'nome', label: 'Nome completo', obrigatorio: 1);
        $Painel->cpf(name: 'cpf', label: 'CPF', obrigatorio: 1);
        $Painel->select(name: 'genero', label: 'Gênero', lista: 'genero');
    });
    $Painel->fieldset('Contato', callback: function () use ($Painel) {
        $Painel->email(name: 'email_trabalho', label: 'E-mail de trabalho', obrigatorio: 1);
        $Painel->email(name: 'email_pessoal', label: 'E-mail pessoal');
        $Painel->telefone(name: 'telefone_trabalho', label: 'Telefone de trabalho');
        $Painel->telefone(name: 'telefone_pessoal', label: 'Telefone pessoal');
    });
    $Painel->fieldset('Dados de acesso', callback: function () use ($Painel) {
        $Painel->select('empresa->id', label: 'Empresa', lista: 'empresa', acao: 'add', permissao: Helper::PERMISSAO_EMPRESA);
        $Painel->senha(
            name: 'senha',
            label: 'Senha de acesso',
            ajuda: 'A senha deve ter 1 letra maiuscula, 1 letra minuscula, 1 número, 1 caracter especial e no mínimo 8 dígitos.'
        );
        $Painel->switch(name: 'primeiro_acesso', label: 'Primeiro acesso?');
        $Painel->switch(name: 'mudar_senha', label: 'Mudar senha ao logar?');
        $Painel->select(name: 'status', label: 'Status', lista: (new Status)->select('Escolha um status'));
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(titulo: 'Permissões', todos: 'Marcar todas as permissões', mais: 1, callback: function () use ($Painel) {
        $permissao = sessao('PAINEL.permissao.montar');
        foreach ($permissao as $ind => $dado) {
            $titulo = $dado['titulo'] ?? '';
            if (!empty($titulo)) {
                $Painel->html('<h4>' . $titulo . '</h4>');
            }
            if (array_key_exists('acao', $dado)) {
                foreach ($dado['acao'] as $acao) {
                    if ($acao == 'index') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Listar', value: $ind . '_index');
                    } else if ($acao == 'visualizar') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Visualizar', value: $ind . '_visualizar');
                    } else if ($acao == 'download') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Download', value: $ind . '_download');
                    } else if ($acao == 'add') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Salvar', value: $ind . '_add');
                    } else if ($acao == 'editar') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Editar', value: $ind . '_editar');
                    } else if ($acao == 'deletar') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Deletar', value: $ind . '_deletar');
                    } else if ($acao == 'status') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Mudar status', value: $ind . '_status');
                    } else if ($acao == 'empresa') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Todas as empresas', value: $ind . '_empresa');
                    } else if ($acao == 'analytics') {
                        $Painel->checkbox(name: 'permissao[]', label: 'Analytics', value: $ind . '_analytics');
                    }
                }
            } else if (array_key_exists('permissao', $dado)) {
                foreach ($dado['permissao'] as $permissaoFinal => $nomePermissao) {
                    $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissaoFinal);
                }
            }
        }
    });
});

return $Painel;
