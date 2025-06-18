<?php

namespace App\Models\Api\Saude\Simulacao;

use App\Classes\SaudeSimulacao\Status;
use ORM\ORM;
use Throwable;

final class NovaSimulacaoModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;

    /**
     * @param int $idUsuario
     */
    public function __construct(int $idUsuario)
    {
        parent::__construct();
        $Status = new Status();
        try {
            $this
                ->dado([
                    'status' => $Status->numero(Status::NOVA_SIMULACAO)
                ])
                ->where([
                    ['id_usuario_cliente', $idUsuario],
                    ['status', $Status->numero(Status::NOVO)]
                ])
                ->update();
        } catch (Throwable) {
            return;
        }
    }
}
