<?php

namespace App\Classes\SolicitacaoContato;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const AGUARDANDO = 'aguardando';
    public const RESPONDIDO = 'respondido';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO       => 'Novo',
            self::AGUARDANDO => 'Aguardando',
            self::RESPONDIDO => 'Respondido'
        ], [
            self::NOVO       => 'azul',
            self::AGUARDANDO => 'amarelo',
            self::RESPONDIDO => 'verde'
        ]);
    }
}
