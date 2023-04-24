<?php

namespace App\Models\Site\Loja;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

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
