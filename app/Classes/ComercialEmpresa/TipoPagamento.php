<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class TipoPagamento extends StatusStatus
{
    public const USUARIO = 'usuario';
    public const FIXO = 'fixo';
    public const MISTO = 'misto';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::USUARIO => 'Por usuário',
            self::FIXO    => 'Valor fixo',
            self::MISTO   => 'Misto',
        ]);
    }
}
