<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;

final class DeletarModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    private array $usuario = [];
    public function __construct()
    {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioCliente\DeletarModel');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }

    public function id($id)
    {
        $usuario = $this->where([
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2, 3, 5]],
            ['cod', $id]
        ])->primeiro();

        $this->validarUsuarioPegarDependente($usuario);
    }
    public function cpf($cpf)
    {
        $usuario = $this->where([
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2, 3, 5]],
            ['documento', soNumero($cpf)]
        ])->primeiro();

        $this->validarUsuarioPegarDependente($usuario);
    }

    private function validarUsuarioPegarDependente($usuario)
    {
        if (existeErro($usuario, 'id')) {
            mensagemStatus(404);
        }

        if ($usuario->tipo == 1) {
            $this->pegarDependentes($usuario->id);
        }

        $this->setarUsuario($usuario);
    }
    private function pegarDependentes($titular)
    {
        $dependente = $this->where([
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2, 3, 5]],
            ['tipo', 2],
            ['titular', $titular]
        ])->read();

        if (!$dependente) {
            return;
        }

        if (!array_key_exists(0, $dependente)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao deletar dependentes, por favor, tente novamente.');
        }

        foreach ($dependente as $r) {
            $this->setarUsuario($r);
        }
    }
    private function setarUsuario($usuario)
    {
        if (empty($usuario->id)) {
            $this->erroPadrao();
        }

        $id = $usuario->id;

        unset($usuario->id);
        unset($usuario->cod);
        unset($usuario->empresa);
        unset($usuario->id_admin_subempresa);
        unset($usuario->titular);
        unset($usuario->tipo);
        unset($usuario->federacao);
        unset($usuario->uf);
        unset($usuario->cidade);
        unset($usuario->data_criacao);
        unset($usuario->data_atualizacao);
        unset($usuario->data_acesso);
        unset($usuario->data_password);
        unset($usuario->data_online);
        unset($usuario->data_upload_tabela);
        unset($usuario->status);

        $dado = [
            'status' => 4
        ];

        foreach (array_keys((array)$usuario) as $val) {
            $dado[$val] = '';
        }

        $this->usuario[] = ['id' => $id, 'dado' => $dado];
    }

    public function deletar()
    {
        foreach ($this->usuario as $r) {
            $id = $r['id'];
            $dado = $r['dado'];
            $deletar = $this->dado($dado)->where([
                ['id', $id],
                ['empresa', $this->idEmpresa]
            ])->update();

            if (existeErro($deletar, 'id')) {
                $this->erroPadrao();
            }
        }
        return true;
    }

    private function erroPadrao()
    {
        mensagemErro('Erro ao deletar!', 'Ocorreu um erro ao deletar usuário, por favor, tente novamente.');
    }
}
