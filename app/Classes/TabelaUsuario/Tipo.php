<?php

namespace App\Classes\TabelaUsuario;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const SALVAR = 'salvar';
    public const BLOQUEAR = 'bloquear';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SALVAR    => 'Salvar',
            self::BLOQUEAR  => 'Bloquear'
        ]);
    }
}
