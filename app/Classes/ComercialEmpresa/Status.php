<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';
    public const PROSPECCAO = 'prospeccao';
    public const STANDBY = 'standby';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO      => 'Ativo',
            self::INATIVO    => 'Inativo',
            self::PROSPECCAO => 'Em prospecção',
            self::STANDBY    => 'Stand-by'
        ], [
            self::ATIVO      => 'verde',
            self::INATIVO    => 'vermelho',
            self::PROSPECCAO => 'azul',
            self::STANDBY    => 'amarelo'
        ]);
    }
}
