<?php

namespace App\Models\Api\Votacao\Dado;

use ORM\ORM;

final class CancelarModel extends ORM
{
    protected string $ormTabela = TABELA_VOTACAO_DADO;

    public function __construct(
        private string $id
    ) {
        parent::__construct();
        $this->verificarSeExiste();
        $this->cancelarVotacao();
    }

    private function verificarSeExiste()
    {
        if (!$this->existe(['uuid', $this->id])) {
            mensagemStatus(404);
        }
    }

    public function cancelarVotacao()
    {
        $this
            ->dado([
                'status' => 3
            ])
            ->where(['uuid', $this->id])
            ->update();
    }
}
