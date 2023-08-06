<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use Helpers\OrmHelper;
use App\Classes\ComercialEmpresa\Helper as HelperEmpresa;

final class AppleModel extends ORM
{
    public const CPF = '01234567890';

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $listaIdEmpresa;
    private array $listaUsuario;
    private string $senha;

    public function __construct()
    {
        parent::__construct();
        $this->senha = password('Teste@1324');

        $this->pegarListaEmpresa();
        $this->montarListaUsuarioPadrao();
        $this->buscarUsuario();
        $this->criarUsuario();
    }

    private function pegarListaEmpresa()
    {
        $Empresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->listaIdEmpresa = $Empresa->pegarListaCampo(
            where: ['status', 'in', HelperEmpresa::STATUS_LIBERADO],
            campo: 'id'
        );
    }

    private function montarListaUsuarioPadrao()
    {
        $retorno = [];
        foreach ($this->listaIdEmpresa as $id) {
            $retorno[$id] = (object)[
                'id_admin_empresa' => $id,
                'status'           => 'nao-existe'
            ];
        }
        $this->listaUsuario = $retorno;
    }

    private function buscarUsuario()
    {
        $dado = $this
            ->campo(['id', 'id_admin_empresa', 'status'])
            ->where([
                ['cpf', self::CPF],
                ['id_admin_empresa', 'in', $this->listaIdEmpresa]
            ])
            ->read();
        if (empty($dado)) {
            return;
        }
        $this->adicionarUsuarioLista($dado);
    }

    private function adicionarUsuarioLista($dado)
    {
        foreach ($dado as $r) {
            $this->listaUsuario[$r->id_admin_empresa] = $r;
        }
    }

    private function criarUsuario()
    {
        foreach ($this->listaUsuario as $r) {
            if ($r->status == 'nao-existe' || !object_key_exists('id', $r)) {
                $this->criarNovoUsuario($r->id_admin_empresa);
                continue;
            }
            $this->atualizarUsuario($r->id);
        }
    }

    private function atualizarUsuario($idUsuario)
    {
        $this
            ->dado([
                'tipo'          => 1,
                'nome'          => 'Usuário Apple',
                'email_pessoal' => 'usuario.apple@markt.club',
                'salt'          => $this->senha,
                'federacao'     => 'FU',
                'mensagem'      => 1,
                'status'        => 1
            ])
            ->where(['id', $idUsuario])
            ->update();
    }

    private function criarNovoUsuario($idEmpresa)
    {
        $this
            ->dado([
                'uuid'             => uuid(),
                'tipo'             => 1,
                'id_admin_empresa' => $idEmpresa,
                'nome'             => 'Usuário Apple',
                'cpf'              => self::CPF,
                'email_pessoal'    => 'usuario.apple@markt.club',
                'salt'             => $this->senha,
                'federacao'        => 'FU',
                'mensagem'         => 1,
                'status'           => 1
            ])
            ->insert();
    }
}
