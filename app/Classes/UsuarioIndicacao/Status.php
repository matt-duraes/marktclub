<?php

namespace App\Classes\UsuarioIndicacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const INDICADO = 'indicado';
    public const ATIVADO = 'ativado';
    public const BLOQUEADO = 'bloqueado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::INDICADO => 'Indicado',
                self::ATIVADO => 'Ativado',
                self::BLOQUEADO => 'Bloqueado'
            ],
            cor: [
                self::INDICADO => 'azul',
                self::ATIVADO => 'verde',
                self::BLOQUEADO => 'vermelho'
            ],
        );
    }
}
