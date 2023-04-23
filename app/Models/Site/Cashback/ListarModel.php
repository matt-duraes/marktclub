<?php

namespace App\models\Site\Cashback;

use stdClass;
use Helpers\ApiHelper;
use App\Models\Site\ListarInterface;
use App\Models\Site\Cashback\MontarRetornoTrait;

final class ListarModel extends ApiHelper implements ListarInterface
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
