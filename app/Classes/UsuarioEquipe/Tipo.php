<?php

namespace App\Classes\UsuarioEquipe;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const TECNOLOGIA = 'tecnologia';
    public const COMERCIAL = 'comercial';
    public const CONVENIO = 'convenio';
    public const COMUNICACAO = 'comunicacao';
    public const FINANCEIRO = 'financeiro';
    public const OUTRO = 'outro';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::TECNOLOGIA  => 'Tecnologia',
            self::COMERCIAL   => 'Comercial',
            self::CONVENIO    => 'Convênio',
            self::COMUNICACAO => 'Comunicação',
            self::FINANCEIRO  => 'Financeiro',
            self::OUTRO       => 'Outro',
        ]);
    }
}
