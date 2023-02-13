<?php

namespace App\Classes\UsuarioIndicacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_INDICADO = 'indicado';
    const STATUS_ATIVADO = 'ativado';
    const STATUS_BLOQUEADO = 'bloqueado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_INDICADO => 'Indicado',
                self::STATUS_ATIVADO => 'Ativado',
                self::STATUS_BLOQUEADO => 'Bloqueado'
            ],
            cor: [
                self::STATUS_INDICADO => 'azul',
                self::STATUS_ATIVADO => 'verde',
                self::STATUS_BLOQUEADO => 'vermelho'
            ],
        );
    }
}
