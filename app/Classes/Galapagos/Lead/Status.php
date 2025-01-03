<?php

namespace App\Classes\Galapagos\Lead;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const FALHA = 'falha';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO    => 'Novo',
            self::FALHA   => 'Falhou',
        ], [
            self::NOVO    => 'vermelho',
            self::FALHA   => 'perto',
        ]);
    }
}
