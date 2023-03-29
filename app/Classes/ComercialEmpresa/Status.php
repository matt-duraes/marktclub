<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const ATIVO = 'ativo';
    const INATIVO = 'inativo';
    const PROSPECCAO = 'prospeccao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ATIVO => 'Ativo',
                self::INATIVO => 'Inativo',
                self::PROSPECCAO => 'Em prospecção'
            ],
            cor: [
                self::ATIVO => 'verde',
                self::INATIVO => 'vermelho',
                self::PROSPECCAO => 'azul',
            ]
        );
    }
}
