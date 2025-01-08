<?php

namespace Tests\Api;

use Tests\Tests;

final class LeadTest extends Tests
{
    protected string $scope = 'galapagos_lead';
    protected string $uri = '/galapagoslead';
    public string $automatico = 's';

    public function __construct()
    {
        $this
            ->tabela(TABELA_GALAPAGOS_LEAD)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [];
    }
}
