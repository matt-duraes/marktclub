<?php

use Modules\Senha;
use Helpers\ApiHelper;
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
        $subempresaLista = ['' => 'Escolha uma empresa'];
        if (sessao('EMPRESA.slug') == 'marktclub') {
            $Painel
                ->select(
                    name: 'empresa->id',
                    label: 'Empresa',
                    lista: 'empresa',
                    acao: 'add',
                    permissao: Helper::PERMISSAO_EMPRESA
                )
                ->hidden(name: 'empresa->id', acao: 'editar', permissao: Helper::PERMISSAO_EMPRESA);
        } else {
            $subempresaLista = (new ApiHelper(token: true))
                ->json([
                    'titulo'  => 'Escolha uma subempresa',
                    'empresa' => sessao('USUARIO.empresa')
                ])
                ->get('/comercial-subempresa/select')
                ->array()['dado'] ?? [];
            if (empty(sessao('USUARIO.subempresa'))) {
                $Painel
                    ->select(
                        name: 'subempresa',
                        label: 'Subempresa',
                        lista: $subempresaLista,
                    );
            }
        }
        $Painel
            ->senha(
                name: 'senha',
                label: 'Senha de acesso',
                ajuda: Senha::MENSAGEM_FORCA_4
            )
            ->switch(name: 'primeiro_acesso', label: 'Primeiro acesso?')
            ->switch(name: 'mudar_senha', label: 'Mudar senha ao logar?')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha um status'));
    });
});

$permissaoUsuario = sessao('USUARIO.permissao');
if (sessao('EMPRESA.slug') != 'marktclub' || in_array('usuario_equipe_permissao', $permissaoUsuario) || sessao('USUARIO.cpf') == '014.951.801-31') {
    $Painel->coluna(callback: function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            titulo: 'Permissões',
            todos: 'Marcar todas as permissões',
            mais: 1,
            callback: function () use ($Painel) {
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
            }
        );
    });
}

$Painel->js('painel_usuario_equipe_add');

return $Painel;
