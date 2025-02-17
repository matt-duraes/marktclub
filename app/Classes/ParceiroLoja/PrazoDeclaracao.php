<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class PrazoDeclaracao extends Status
{
    public const UMA_HORA = 'uma_hora';
    public const TRES_HORA = 'tres_hora';
    public const CINCO_HORA = 'cinco_hora';
    public const OITO_HORA = 'oito_hora';
    public const DOZE_HORA = 'doze_hora';
    public const UM_DIA = 'um_dia';
    public const TRES_DIA = 'tres_dia';
    public const CINCO_DIA = 'cinco_dia';
    public const UMA_SEMANA = 'uma_semana';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::UMA_HORA   => '1h',
            self::TRES_HORA  => '3h',
            self::CINCO_HORA => '5h',
            self::OITO_HORA  => '8h',
            self::DOZE_HORA  => '12h',
            self::UM_DIA     => '1 dia',
            self::TRES_DIA   => '3 dias',
            self::CINCO_DIA  => '5 dias',
            self::UMA_SEMANA => '7 dias'
        ]);
    }
}
