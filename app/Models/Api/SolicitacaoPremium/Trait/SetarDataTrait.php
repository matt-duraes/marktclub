<?php

namespace App\Models\Api\SolicitacaoPremium\Trait;

use Modules\Data;

trait SetarDataTrait
{
    private function setarPrimeiroUltimoDia()
    {
        $data = new Data(!empty($this->request->data) ? $this->request->data : hoje());
        $this->de = dataPrimeiroDiaMes($data->date());
        $this->ate = dataUltimoDiaMes($data->date(), formato: 'Y-m-d H:i:s');
    }
}
