<?php

namespace App\Classes\UsuarioIndicacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const INDICADO = 'indicado';
    const ATIVADO = 'ativado';
    const BLOQUEADO = 'bloqueado';

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
