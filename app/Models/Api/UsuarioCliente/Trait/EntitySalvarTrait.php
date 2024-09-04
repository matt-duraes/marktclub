<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\UsuarioGrupo\GrupoEntity;
use Helpers\OrmHelper;

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
        $this->setarSubempresa();
        $this->tipo = new TipoUsuario(TipoUsuario::TITULAR);
        if (!empty($this->trabalho_empresa) && (new TrabalhoEmpresa($this->trabalho_empresa))->valido()) {
            $this->trabalho_empresa = (new TrabalhoEmpresa($this->trabalho_empresa))->numero();
        }
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

        if (!empty($this->request->grupo)) {
            $this->grupo = strCaixaBaixa($this->request->grupo);
        }
    }

    private function setarSubempresa()
    {
        if (!$this->propriedadeExiste('subempresa')) {
            return;
        } elseif (empty($this->subempresa)) {
            $this->id_admin_subempresa = 0;
            return;
        }
        $idSubempresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->subempresa);
        if (empty($idSubempresa)) {
            mensagemErro('Campo inválido!', 'Não foi encontrado nenhuma subempresa pelo código enviado', status: 404);
        }
        $this->id_admin_subempresa = $idSubempresa;
    }
}
