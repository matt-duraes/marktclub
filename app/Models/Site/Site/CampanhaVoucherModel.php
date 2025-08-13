<?php

namespace App\Models\Site\Site;

use App\Helpers\ClubeApiHelper;
use Erro\Excecao;

class CampanhaVoucherModel extends ClubeApiHelper
{
    /**
     * @throws Excecao
     */
    public function verificar(): object|bool|array
    {
        return $this->get('/campanha-voucher-disponivel')->object();
    }
}
