<?php

use Modules\Senha;
use Helpers\ApiHelper;
use App\Classes\UsuarioEquipe\Helper;
use App\Classes\UsuarioEquipe\Status;
use PainelConfig\Add;

$Painel = new Add('usuario_equipe', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', callback: function () use ($Painel) {
        $Painel->input(name: 'nome', label: 'Nome completo', obrigatorio: 1);
        $Painel->cpf(name: 'cpf', label: 'CPF', obrigatorio: 1);
        $Painel->select(name: 'genero', lista: 'genero', label: 'Gênero');
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
                    lista: 'empresa',
                    label: 'Empresa',
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
                        lista: $subempresaLista,
                        label: 'Subempresa',
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
            ->select(name: 'status', lista: (new Status())->select('Escolha um status'), label: 'Status');
    });
});

$permissaoUsuario = sessao('USUARIO.permissao');
if (
    sessao('USUARIO.cpf') == '014.951.801-31'
    || in_array('usuario_equipe_permissao', $permissaoUsuario)
) {
    $Painel->coluna(callback: function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            titulo: 'Permissões',
            callback: function () use ($Painel) {
                $permissoes = sessao('PAINEL.permissao.montar');
                foreach ($permissoes as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });
}

$Painel->js('painel_usuario_equipe_add');

return $Painel;
