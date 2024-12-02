<?php

namespace PainelApp\login\Models;

use Helpers\ApiHelper;

final class PainelModel
{
    public function __construct()
    {
        $this->pegandoPermissaoDoPainel();
        $this->pegandoListaMenu();
    }

    private function pegandoPermissaoDoPainel()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->headerJson()
            ->get('/admin/painel')
            ->object();
        if (!object_key_exists('dado', $dado)) {
            $dado = (object)[
                'dado' => (object)[
                    'permissao'         => [],
                    'upload_grupo'      => [],
                    'configuracao'      => [],
                    'campo_permitido'   => [],
                    'campo_obrigatorio' => [],
                ]
            ];
        }
        $dado = $dado->dado;
        sessao('PAINEL.permissao', $dado->permissao);
        sessao('PAINEL.obrigatorio', $this->setarCampoObrigatorio($dado->campo_obrigatorio));
        sessao('PAINEL.configuracao', $this->setarConfiguracao($dado->configuracao));
        sessao('PAINEL.upload_grupo', $dado->upload_grupo);
        sessao('PAINEL.campo', $this->setarCampoPermitido($dado->campo_permitido));
    }

    private function setarCampoObrigatorio($dado)
    {
        return !empty($dado) ? $dado : [
            'usuario_cliente' => [
                'cpf',
                'email',
                'status'
            ]
        ];
    }

    private function setarConfiguracao($dado)
    {
        return !empty($dado) ? $dado : ['perfil', 'bloquear'];
    }

    private function setarCampoPermitido($dado)
    {
        return !empty($dado) ? $dado : [
            'usuario_cliente' => [
                'nome', 'cpf', 'matricula', 'siape', 'genero', 'data_nascimento',
                'email', 'telefone', 'endereco_estado',
                'endereco_cidade', 'senha', 'status', 'primeiro_acesso', 'mudar_senha', 'estado_civil'
            ]
        ];
    }

    private function pegandoListaMenu()
    {
        $Api = new ApiHelper(token: true);
        $menu = $Api->headerJson()->get('/admin/menu')->array();
        $menu = array_key_exists('dado', $menu) ? $menu['dado'] : [];
        sessao('PAINEL.menu', $menu);
    }
}
