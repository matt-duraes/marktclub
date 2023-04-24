<?php

namespace App\Classes\DownloadPrivado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const VISUALIZADO = 'visualizado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO        => 'Novo',
            self::VISUALIZADO => 'Visualizado'
        ]);
    }
}
