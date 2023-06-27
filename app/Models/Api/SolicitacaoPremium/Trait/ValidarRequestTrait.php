<?php

namespace App\Models\Api\SolicitacaoPremium\Trait;

use Modules\Data;

trait ValidarRequestTrait
{
    private function validarRequest()
    {
        $dataDe = new Data($this->request->data_de);
        $dataAte = new Data($this->request->data_ate);
        if (!$dataDe->vazio() && !$dataDe->eDate()) {
            mensagemErro('Campo inválido!', 'A data de inicio informada não é válida.');
        } elseif (!$dataAte->vazio() && !$dataAte->eDate()) {
            mensagemErro('Campo inválido!', 'A data informada não é válida.');
        }
    }
}
