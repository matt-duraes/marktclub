<?php

namespace App\Models\Site\Cashback;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class RelacionadoModel extends ClubeApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }
}
