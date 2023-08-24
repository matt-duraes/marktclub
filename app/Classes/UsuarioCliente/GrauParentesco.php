<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class GrauParentesco extends StatusStatus
{
    public const PAI = 'pai';
    public const MAE = 'mae';
    public const CONJUGE = 'conjuge';
    public const FILHO = 'filho';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PAI     => 'Pai',
            self::MAE     => 'Mãe',
            self::CONJUGE => 'Cônjuge',
            self::FILHO   => 'Filho'
        ]);
    }
}
