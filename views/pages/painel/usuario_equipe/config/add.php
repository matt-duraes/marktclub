<?php

use App\Classes\Painel\Config\Padrao;
use App\Classes\UsuarioEquipe\Helper;
use App\Classes\UsuarioEquipe\Status;
use App\Classes\UsuarioEquipe\Tipo;
use Helpers\ApiHelper;
use Modules\Senha;
use PainelConfig\Add;

$Painel = new Add('usuario_equipe', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->input(name: 'nome', label: 'Nome completo', obrigatorio: 1)
            ->cpf(name: 'cpf', label: 'CPF', obrigatorio: 1)
            ->select(name: 'genero', lista: 'genero', label: 'Gênero');
        if (sessao('EMPRESA.slug') != 'marktclub') {
            $Painel->html('<input name="tipo" value="outro">', acao: 'add');
        } else {
            $Painel->select(
                name: 'tipo',
                lista: (new Tipo())->select('Escolha uma opção'),
                label: 'Local de trabalho'
            );
        }
    });
    $Painel->fieldset('Contato', callback: function () use ($Painel) {
        $Painel->email(name: 'email_trabalho', label: 'E-mail de trabalho', obrigatorio: 1);
        $Painel->email(name: 'email_pessoal', label: 'E-mail pessoal');
        $Painel->telefone(name: 'telefone_trabalho', label: 'Telefone de trabalho');
        $Painel->telefone(name: 'telefone_pessoal', label: 'Telefone pessoal');
    });
    $Painel->fieldset('Dados de acesso', callback: function () use ($Painel) {
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
        } elseif (empty(sessao('USUARIO.subempresa'))) {
            $subempresaLista = (new ApiHelper(token: true))
                ->json([
                    'titulo'  => 'Escolha uma subempresa',
                    'empresa' => sessao('USUARIO.empresa')
                ])
                ->get('/comercial-subempresa/select')
                ->array()['dado'] ?? [];

            $Painel
                ->select(
                    name: 'subempresa',
                    lista: $subempresaLista,
                    label: 'Subempresa'
                );
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
if (in_array('usuario_equipe_permissao', $permissaoUsuario)) {
    $Painel->coluna(callback: function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            titulo: 'Permissões',
            callback: function () use ($Painel) {
                $permissaoPainel = sessao('PAINEL.permissao');
                foreach (Padrao::PERMISSOES as $configuracoes) {
                    $tituloApp = $configuracoes['titulo'] ?? '';
                    $temTitulo = false;
                    foreach ($configuracoes['permissao'] as $permissao => $configPermissao) {
                        if (!in_array($permissao, $permissaoPainel)) {
                            continue;
                        }
                        if (!empty($tituloApp) && !$temTitulo) {
                            $Painel->html('<h4>' . $tituloApp . '</h4>');
                        }
                        $temTitulo = true;
                        $Painel->checkbox(
                            name: 'permissao[]',
                            label: is_string($configPermissao)
                                ? $configPermissao
                                : $configPermissao['titulo'],
                            value: $permissao
                        );
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
