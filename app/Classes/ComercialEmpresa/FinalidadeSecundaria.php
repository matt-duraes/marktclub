<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class FinalidadeSecundaria extends StatusStatus
{
    public const ASSOCIACAO = 'associacao';
    public const SINDICATO = 'sindicato';
    public const EMBAIXADA = 'embaixada';
    public const CONSELHO = 'conselho';
    public const FACULDADE = 'faculdade';
    public const BANCO = 'banco';
    public const OUTRO = 'outro';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ASSOCIACAO => 'Associação',
                self::SINDICATO => 'Sindicato',
                self::EMBAIXADA => 'Embaixada',
                self::CONSELHO => 'Conselho de classe',
                self::FACULDADE => 'Faculdade',
                self::BANCO => 'Banco',
                self::OUTRO => 'Outro',
            ]
        );
    }
}
