<?php

namespace App\Classes\Popup;

use Status\Status as StatusStatus;

class Status extends StatusStatus
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
        ], [1, -1]);
    }
}
