<?php

namespace App\Classes\UsuarioIndicacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const INDICADO = 'indicado';
    public const ATIVADO = 'ativado';
    public const BLOQUEADO = 'bloqueado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::INDICADO  => 'Indicado',
            self::ATIVADO   => 'Ativado',
            self::BLOQUEADO => 'Bloqueado'
        ], [
            self::INDICADO  => 'azul',
            self::ATIVADO   => 'verde',
            self::BLOQUEADO => 'vermelho'
        ]);
    }
}
