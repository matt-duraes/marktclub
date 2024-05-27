<?php

namespace App\Classes\Silium;

use Status\Status as StatusStatus;

class StatusDeposito extends StatusStatus
{
    public const NOVO = 'novo';
    public const AGUARDANDO = 'aguardando';
    public const DEPOSITADO = 'depositado';
    public const NEGADO = 'negado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO       => 'Novo',
            self::AGUARDANDO => 'Aguardando Análise',
            self::DEPOSITADO => 'Depositado',
            self::NEGADO     => 'Negado'
        ], [
            self::NOVO       => 'azul',
            self::AGUARDANDO => 'amarelo',
            self::DEPOSITADO => 'verde',
            self::NEGADO     => 'vermelho'
        ]);
    }
}
