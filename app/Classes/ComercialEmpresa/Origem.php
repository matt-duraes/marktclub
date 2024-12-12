<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class Origem extends StatusStatus
{
    public const INDICACAO = 'indicacao';
    public const PROSPECCAO = 'prospeccao';
    public const DEMANDA_ESPONTANEA = 'demanda_espontanea';
    public const CAMPANHA_INDICACAO = 'campanha-indicacao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::INDICACAO          => 'Indicação',
            self::PROSPECCAO         => 'Prospecção',
            self::DEMANDA_ESPONTANEA => 'Demanda espontânea',
            self::CAMPANHA_INDICACAO => 'Campanha de indicação',
        ]);
    }
}
