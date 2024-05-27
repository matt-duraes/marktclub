<?php

namespace App\Classes\Silium;

use Status\Status as StatusStatus;

class StatusComissao extends StatusStatus
{
    public const NOVO = 'novo';
    public const AGUARDANDO = 'aguardando';
    public const CREDITADO = 'creditado';
    public const NEGADO = 'negado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO       => 'Novo',
            self::AGUARDANDO => 'Aguardando Análise',
            self::CREDITADO  => 'Creditado',
            self::NEGADO     => 'Negado'
        ], [
            self::NOVO       => 'azul',
            self::AGUARDANDO => 'amarelo',
            self::CREDITADO  => 'verde',
            self::NEGADO     => 'vermelho'
        ]);
    }
}
