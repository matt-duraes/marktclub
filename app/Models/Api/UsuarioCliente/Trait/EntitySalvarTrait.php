<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\UsuarioGrupo\GrupoEntity;

trait EntitySalvarTrait
{
    protected function regraSalvar()
    {
        $this->cpfExiste();
        $this->emailTrabalhoExiste();
        $this->emailPessoalExiste();
        $this->matriculaExiste();
        $this->siapeExiste();
        $this->grupoValido();
        $this->tipo = new TipoUsuario(TipoUsuario::TITULAR);
    }

    private function grupoValido()
    {
        $Grupo = new GrupoEntity();
        if (
            !empty($this->request->grupo) &&
            !$Grupo->existe([
                ['indice', $this->grupo],
                ['id_admin_empresa', $this->idEmpresa]
            ])
        ) {
            mensagemErro('Campo inválido!', 'O grupo informado não é um valor válido.');
        }
    }
}
