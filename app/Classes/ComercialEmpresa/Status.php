<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';
    public const PROSPECCAO = 'prospeccao';

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
