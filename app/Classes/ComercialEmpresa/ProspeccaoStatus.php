<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class ProspeccaoStatus extends StatusStatus
{
    public const ABORDAGEM = 'abordagem';
    public const APRESENTACAO = 'apresentacao';
    public const NEGOCIACAO = 'negociacao';
    public const AVALIACAO = 'avaliacao';
    public const MINUTA = 'minuta';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ABORDAGEM => 'Abordagem',
                self::APRESENTACAO => 'Apresentação',
                self::NEGOCIACAO => 'Nogociação',
                self::AVALIACAO => 'Em avaliação',
                self::MINUTA => 'Minuta enviada',
            ]
        );
    }
}
