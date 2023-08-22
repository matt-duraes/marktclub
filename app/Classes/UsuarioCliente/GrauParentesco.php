<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class GrauParentesco extends StatusStatus
{
    public const PAI = 'ativo';
    public const MAE = 'inativo';
    public const CONJUGE = 'bloqueado';
    public const FILHO = 'indicacao';

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
