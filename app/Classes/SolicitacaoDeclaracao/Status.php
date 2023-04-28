<?php

namespace App\Classes\SolicitacaoDeclaracao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const CRIADA = 'criada';
    public const VALIDADA = 'validada';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADA   => 'Criada',
            self::VALIDADA => 'Validada'
        ], [
            self::CRIADA   => 'azul',
            self::VALIDADA => 'verde'
        ]);
    }
}
