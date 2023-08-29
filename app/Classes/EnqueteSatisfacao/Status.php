<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const VISUALIZADA = 'visualizada';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO        => 'Novo',
            self::VISUALIZADA => 'Visualizada'
        ], [
            self::NOVO        => 'azul',
            self::VISUALIZADA => 'verde'
        ]);
    }
}
