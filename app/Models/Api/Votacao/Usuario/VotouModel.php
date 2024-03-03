<?php

namespace App\Models\Api\Votacao\Usuario;

use ORM\ORM;

final class VotouModel extends ORM
{
    protected string $ormTabela = TABELA_VOTACAO_USUARIO;

    public function __construct(
        private int $idUsuario,
        private int $idVotacao
    ) {
        parent::__construct();
        $this->validarCampo();
        $this->salvar();
    }

    private function validarCampo()
    {
        if (empty($this->idUsuario) || empty($this->idVotacao)) {
            mensagemStatus(400);
        }
    }

    private function salvar()
    {
        $this
            ->dado([
                'id_usuario_cliente' => $this->idUsuario,
                'id_votacao_dado'    => $this->idVotacao,
                'ordem'              => rand(1, 9999)
            ])
            ->insert();
    }
}
