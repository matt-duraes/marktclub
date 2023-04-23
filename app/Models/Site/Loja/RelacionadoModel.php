<?php

namespace App\models\Site\Loja;

use stdClass;
use Helpers\ApiHelper;
use App\Models\Site\ListarInterface;
use App\Models\Site\Loja\MontarRetornoTrait;

final class RelacionadoModel extends ApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function __construct()
    {
        parent::__construct(scope: '');
    }
    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }
}
