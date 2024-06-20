<?php

namespace App\Models\Api\Silium;

use Modules\Data;
use ORM\Entity;

class SiliumSaldoEntity extends Entity
{
    protected string $ormTabela = TABELA_SILIUM_SALDO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'saldo_silium', 'data_validade',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_usuario_cliente'
    ];
    protected array $ormSalvar = [
        'saldo_silium', 'data_validade'
    ];
    protected int $id_usuario_cliente;

    public int $saldo_silium;
    public Data $data_validade;

    public function __construct()
    {
        parent::__construct();
    }
}
