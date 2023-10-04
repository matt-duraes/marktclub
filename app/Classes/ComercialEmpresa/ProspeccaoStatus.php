<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class ProspeccaoStatus extends StatusStatus
{
    public const PESQUISA = 'pesquisa';
    public const APRESENTACAO = 'apresentacao';
    public const NEGOCIACAO = 'negociacao';
    public const AVALIACAO = 'avaliacao';
    public const MINUTA = 'minuta';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PESQUISA     => 'Pesquisa',
            self::APRESENTACAO => 'Apresentação',
            self::NEGOCIACAO   => 'Nogociação',
            self::AVALIACAO    => 'Em avaliação',
            self::MINUTA       => 'Minuta enviada',
        ]);
    }
}
