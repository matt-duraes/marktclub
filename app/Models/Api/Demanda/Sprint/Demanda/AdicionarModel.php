<?php

namespace App\Models\Api\Demanda\Sprint\Demanda;

use App\Classes\Demanda\Sprint\Status;

final class AdicionarModel extends DemandaModel
{
    public function __construct(
        protected string $sprint,
        protected string $demanda,
        protected string $texto = ''
    ) {
        parent::__construct();
        $this->validarDemanda();
        $this->adicionar();
    }

    private function validarDemanda()
    {
        if (in_array($this->demanda, $this->demandaId)) {
            mensagemErro('Demanda já existe!', 'A demanda que deseja adicionar já está na sprint.');
        }
    }

    private function adicionar()
    {
        $this->demandaId[] = $this->demanda;
        if ($this->demandaStatus == Status::ANDAMENTO) {
            $this->demandaAdicionada = $this->adicionarMensagem($this->demandaAdicionada);
        }
        $this
            ->dado([
                'id_demanda'            => $this->demandaId,
                'id_demanda_adicionada' => $this->demandaAdicionada
            ])
            ->where(['id', $this->id])
            ->update();
    }
}
