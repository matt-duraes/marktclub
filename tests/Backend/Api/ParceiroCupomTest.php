<?php

namespace Tests\Api;

use App\Classes\ParceiroCupom\Status;
use Tests\Tests;

class ParceiroCupomTest extends Tests
{
    protected string $scope = 'parceiro_cupom';
    protected string $uri = '/parceiro-cupom';
    public string $automatico = 'ru';

    public function pegarBody()
    {
        return [
            'status' => Status::CANCELADO,
        ];
    }
}
