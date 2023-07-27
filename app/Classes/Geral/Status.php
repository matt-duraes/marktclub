<?php

namespace App\Classes\Geral;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO   => 'Ativo',
            self::INATIVO => 'Inativo'
        ], [
            self::ATIVO   => 'verde',
            self::INATIVO => 'vermelho'
        ]);
    }
}
