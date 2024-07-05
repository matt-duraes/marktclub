<?php

namespace App\Models\Api\Demanda\Sprint\Demanda;

use App\Classes\Demanda\Sprint\Status;

final class RemoverModel extends DemandaModel
{
    public function __construct(
        protected string $sprint,
        protected string $demanda,
        protected string $texto = ''
    ) {
        parent::__construct();
        $this->validarDemanda();
        $this->remover();
    }

    private function validarDemanda()
    {
        if (!in_array($this->demanda, $this->demandaId)) {
            mensagemErro('Demanda não existe!', 'A demanda que deseja remover não está nessa sprint.');
        }
    }

    private function remover()
    {
        $demanda = array_flip($this->demandaId);
        unset($demanda[$this->demanda]);
        $this->demandaId = array_keys($demanda);

        if ($this->demandaStatus == Status::ANDAMENTO) {
            $this->demandaRetirada = $this->adicionarMensagem($this->demandaRetirada);
        }

        $this
            ->dado([
                'id_demanda'            => $this->demandaId,
                'id_demanda_retirada'   => $this->demandaRetirada
            ])
            ->where(['id', $this->demandaId])
            ->update();
    }
}
