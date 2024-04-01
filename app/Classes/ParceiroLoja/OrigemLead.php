<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class OrigemLead extends Status
{
    public const INDICACAO = 'indicacao';
    public const OPERADOR = 'operador';
    public const CONCORRENCIA = 'concorrencia';
    public const ESPONTANEA = 'espontanea';
    public const CAMPANHA = 'campanha';
    public const OUTRO = 'outro';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::INDICACAO    => 'Indicação',
            self::OPERADOR     => 'Operador',
            self::CONCORRENCIA => 'Concorrência',
            self::ESPONTANEA   => 'Espontânea',
            self::CAMPANHA     => 'Campanha',
            self::OUTRO        => 'Outro',
        ]);
    }
}
