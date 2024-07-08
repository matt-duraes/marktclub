<?php

namespace App\Models\Api\SiliumSaldo;

use ORM\Entity;

class SiliumSaldoEntity extends Entity
{
    public int|null $saldo_silium;
    protected string $ormTabela = TABELA_SILIUM_SALDO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'saldo_silium',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_usuario_cliente', 'saldo_silium'
    ];
    protected int $id_usuario_cliente;

    public function __construct()
    {
        parent::__construct();
    }
}
