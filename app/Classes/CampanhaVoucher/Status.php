<?php

namespace App\Classes\CampanhaVoucher;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NAO_RESGATADO = 'nao_resgatado';
    public const RESGATADO = 'resgado';
    public const VENCIDO = 'vencido';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NAO_RESGATADO => 'Não Resgatado',
            self::RESGATADO     => 'Resgatado',
            self::VENCIDO       => 'Vencido'
        ], [
            self::NAO_RESGATADO => 'azul',
            self::RESGATADO     => 'verde',
            self::VENCIDO       => 'vermelho'
        ]);
    }
}
