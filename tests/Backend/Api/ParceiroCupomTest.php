<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\ParceiroCupom\Status;

class ParceiroCupomTest extends Tests
{
    protected string $scope = 'parceiro_cupom';
    protected string $uri = '/parceiro-cupom';
    public string $automatico = 'lba';

    public function pegarBody()
    {
        return [
            'status' => Status::CANCELADO,
        ];
    }
}
