<?php

namespace App\Classes\SiliumDeposito;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const AGUARDANDO = 'aguardando';
    public const DEPOSITADO = 'depositado';
    public const NEGADO = 'negado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AGUARDANDO => 'Aguardando Análise',
            self::DEPOSITADO => 'Depositado',
            self::NEGADO     => 'Negado'
        ], [
            self::AGUARDANDO => 'amarelo',
            self::DEPOSITADO => 'verde',
            self::NEGADO     => 'vermelho'
        ]);
    }
}
