<?php

namespace System\Classes\PainelNotificacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const VISUALIZADO = 'visualizado';
    public const CLICADO = 'clicado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::NOVO        => 'Novo',
            self::VISUALIZADO => 'Visualizado',
            self::CLICADO     => 'Clicado'
        ]);
    }
}
