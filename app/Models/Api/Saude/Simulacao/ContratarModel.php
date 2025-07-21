<?php

namespace App\Models\Api\Saude\Simulacao;

use ORM\ORM;
use App\Classes\SaudeSimulacao\Status;

final class ContratarModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;

    public int $id;

    public function __construct(
        ?string $uuid = null
    )
    {
        parent::__construct();
        $this->pegarIdPeloUuid($uuid);
    }

    public function contratado()
    {
        $this
            ->dado([
                'status' => new Status(Status::ENVIADO)
            ])
            ->where(['id', $this->id])
            ->update();
    }

    private function pegarIdPeloUuid(?string $uuid)
    {
        if(empty($uuid)) {
            $this->erroPadrao();
        }
        $id = $this->where(['uuid', $uuid])->primeiro('id');
        if(empty($id)) {
            $this->erroPadrao();
        }
        $this->id = $id;
    }

    private function erroPadrao()
    {
        mensagemErro('Erro!', 'Ocorre um erro ao buscar sua simulação, por favor, tente novamente.', status: 400);
    }
}
