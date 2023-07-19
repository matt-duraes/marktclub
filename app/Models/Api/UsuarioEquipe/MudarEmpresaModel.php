<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\ORM;
use Helpers\OrmHelper;

final class MudarEmpresaModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_EQUIPE;

    public function __construct(string $empresa)
    {
        parent::__construct();
        $idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario'])
            ? TOKEN['usuario']->get('id') : 0;
        $idEmpresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA, false))->pegarIdPeloUuid($empresa);
        $this->validarIdUsuarioEmpresa($idUsuario, $idEmpresa);
        $this->atualizarEmpresa($idUsuario, $idEmpresa);
    }

    private function validarIdUsuarioEmpresa(int $usuario, int $empresa): void
    {
        if (empty($usuario)) {
            mensagemErro('Dado inválido!', 'Nenhum usuário encontrado.', status: 403);
        } elseif (empty($empresa)) {
            mensagemErro('Dado inválido!', 'Nenhuma empresa encontrada.', status: 404);
        }
    }

    private function atualizarEmpresa(int $usuario, int $empresa): void
    {
        $this->dado(['id_admin_empresa' => $empresa])->where(['id', $usuario])->update();
    }
}
