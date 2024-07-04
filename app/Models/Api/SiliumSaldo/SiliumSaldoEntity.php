<?php

namespace App\Models\Api\SiliumSaldo;

use ORM\Entity;

class SiliumSaldoEntity extends Entity
{
    protected string $ormTabela = TABELA_SILIUM_SALDO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'saldo_silium',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_usuario_cliente', 'saldo_silium'
    ];
    protected int $id_usuario_cliente;

    public int $saldo_silium;

    public function __construct()
    {
        parent::__construct();
    }
}
