<?php

namespace App\Classes\EnqueteMercado;

use Status\Status;

class Produtos extends Status
{
    public const ELETRONICOS = 'eletronicos';
    public const TURISMO = 'turismo';
    public const MEDICAMENTO = 'medicamento';
    public const ELETRODOMENTICOS = 'eletrodomesticos';
    public const VEICULOS = 'veiculos';
    public const DECORACAO = 'decoracao';
    public const VIAGEM = 'viagem';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ELETRONICOS      => 'Eletrônicos',
            self::TURISMO          => 'Turismo',
            self::MEDICAMENTO      => 'Medicamento',
            self::ELETRODOMENTICOS => 'Eletrodomésticos',
            self::VEICULOS         => 'Veículos',
            self::DECORACAO        => 'Decoração',
            self::VIAGEM           => 'Viagem'
        ]);
    }
}
