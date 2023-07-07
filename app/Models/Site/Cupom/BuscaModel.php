<?php

namespace App\Models\Site\Cupom;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class BuscaModel extends ClubeApiHelper
{
    use MontarRetornoTrait;

    public function listarDados(string $url = null): stdClass
    {
        $dado = $this->get('/parceiro-cupom/' . $url)->object();
        return $this->montarRetorno($dado);
    }
}
