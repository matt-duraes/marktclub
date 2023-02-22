<?php

namespace App\Classes\UsuarioEquipe;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const ATIVO = 'ativo';
    const INATIVO = 'inativo';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ATIVO => 'Ativo',
                self::INATIVO => 'Inativo',
            ],
            cor: [
                self::ATIVO => 'verde',
                self::INATIVO => 'vermelho',
            ],
        );
    }
}
