<?php

namespace App\Models\Api\Votacao\Usuario;

use ORM\ORM;

final class VotouModel extends ORM
{
    protected string $ormTabela = TABELA_VOTACAO_USUARIO;

    public function __construct(
        private array $usuario,
        private int $idVotacao
    ) {
        parent::__construct();
        $this->salvar();
    }

    private function salvar()
    {
        $usuario = $this->usuario;
        $this
            ->dado([
                'id_votacao_dado'    => $this->idVotacao,
                'id_usuario_cliente' => $usuario['id'],
                'nome'               => $usuario['nome'],
                'cpf'                => $usuario['cpf'],
                'ordem'              => rand(1, 9999)
            ])
            ->insert();
    }
}
