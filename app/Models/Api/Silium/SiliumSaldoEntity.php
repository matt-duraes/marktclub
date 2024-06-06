<?php

namespace App\Models\Api\Silium;

use ORM\Entity;

class SiliumSaldoEntity extends Entity
{
    protected string $ormTabela = TABELA_SILIUM_SALDO;
    protected array $ormBuscar = [
        'saldo'
    ];
    protected array $ormSalvar = [
        'saldo'
    ];
    public int $saldo;

    public function __construct()
    {
        parent::__construct();
    }
}
