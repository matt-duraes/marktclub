<?php

namespace App\Models\Api\SolicitacaoPremium\Trait;

use Modules\Data;
use Helpers\DataHelper;

trait SetarDataTrait
{
    private bool $mesAtualInteiro = false;
    private bool $mesAtual = false;

    private function setarDadoDaData()
    {
        $De = new Data($this->request->data_de);
        $Ate = new Data($this->request->data_ate);

        $primeiroDia = dataPrimeiroDiaMes(hoje());
        $ultimoDia = dataUltimoDiaMes(hoje());

        $this->de = $De->valido() ? $De->date() : $primeiroDia;
        $this->ate = $Ate->valido() ? $Ate->date() : $ultimoDia;
        $this->setarSeMesAtual($primeiroDia, $ultimoDia);
    }

    private function setarSeMesAtual($de, $ate)
    {
        if ($de == $this->de && $ate == $ate) {
            $this->mesAtual = true;
            $this->mesAtualInteiro = true;
            return;
        }
        $de = (new DataHelper($this->de))->formato('Y-m');
        $ate = (new DataHelper($this->ate))->formato('Y-m');
        $comparacao = (new DataHelper(hoje()))->formato('Y-m');
        if ($de == $ate && $de == $comparacao) {
            $this->mesAtual = true;
        }
    }
}
