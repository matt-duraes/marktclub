<?php

namespace PainelApp\login\Models;

use Helpers\ApiHelper;

final class PainelModel
{
    public function __construct()
    {
        $this->pegandoPermissaoDoPainel();
        $this->pegandoCampoObrigatorio();
        $this->pegandoConfiguracaoDoPainel();
        $this->pegandoUploadGrupoDoPainel();
        $this->pegandoCampoPermitidos();
        $this->pegandoListaMenu();
    }

    private function pegandoPermissaoDoPainel()
    {
        $Api = new ApiHelper(token: true);

        $permissaoMontar = $Api->headerJson()->get('/admin/permissao')->array();
        $permissaoMontar = array_key_exists('dado', $permissaoMontar) ? $permissaoMontar['dado'] : [];
        $permissaoLista = [];
        foreach ($permissaoMontar as $nomeApp => $permissoesApp) {
            if (array_key_exists('acao', $permissoesApp) && !empty($permissoesApp['acao'])) {
                foreach ($permissoesApp['acao'] as $permissao) {
                    $permissaoLista[] = $nomeApp . '_' . $permissao;
                }
            } elseif (array_key_exists('permissao', $permissoesApp)) {
                foreach (array_keys($permissoesApp['permissao']) as $permissao) {
                    $permissaoLista[] = $permissao;
                }
            }
        }

        $permissaoLista = array_unique($permissaoLista);

        sessao('PAINEL.permissao.montar', $permissaoMontar);
        sessao('PAINEL.permissao.lista', $permissaoLista);
    }

    private function pegandoCampoObrigatorio()
    {
        $Api = new ApiHelper(token: true);
        $obrigatorio = $Api->headerJson()->get('/admin/campo-obrigatorio')->array();
        $obrigatorio = array_key_exists('dado', $obrigatorio) ? $obrigatorio['dado'] : [
            'usuario_cliente' => [
                'cpf',
                'email',
                'status'
            ]
        ];

        sessao('PAINEL.obrigatorio', $obrigatorio);
    }

    private function pegandoConfiguracaoDoPainel()
    {
        $Api = new ApiHelper(token: true);
        $configuracao = $Api->headerJson()->get('/admin/configuracao')->array();
        $configuracao = array_key_exists('dado', $configuracao) ? $configuracao['dado'] : ['perfil', 'bloquear'];

        sessao('PAINEL.configuracao', $configuracao);
    }

    private function pegandoUploadGrupoDoPainel()
    {
        $Api = new ApiHelper(token: true);
        $grupo = $Api->headerJson()->get('/admin/upload-grupo')->array();
        $grupo = array_key_exists('dado', $grupo) ? $grupo['dado'] : [];

        sessao('PAINEL.upload_grupo', $grupo);
    }

    private function pegandoCampoPermitidos()
    {
        $Api = new ApiHelper(token: true);
        $campo = $Api->headerJson()->get('/admin/campo-permitido')->array();
        $campo = array_key_exists('dado', $campo) ? $campo['dado'] : [
            'usuario_cliente' => [
                'nome', 'cpf', 'matricula', 'siape', 'genero', 'data_nascimento',
                'email', 'telefone', 'endereco_estado',
                'endereco_cidade', 'senha', 'status', 'primeiro_acesso', 'mudar_senha', 'estado_civil'
            ]
        ];

        sessao('PAINEL.campo', $campo);
    }

    private function pegandoListaMenu()
    {
        $Api = new ApiHelper(token: true);
        $menu = $Api->headerJson()->get('/admin/menu')->array();
        $menu = array_key_exists('dado', $menu) ? $menu['dado'] : [];
        sessao('PAINEL.menu', $menu);
    }
}
