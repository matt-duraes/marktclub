<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';
    public const BLOQUEADO = 'bloqueado';
    public const INDICACAO = 'indicacao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO     => 'Ativo',
            self::INATIVO   => 'Inativo',
            self::BLOQUEADO => 'Bloqueado',
            self::INDICACAO => 'Indicação'
        ], [
            self::ATIVO     => 'verde',
            self::INATIVO   => 'azul',
            self::BLOQUEADO => 'vermelho',
            self::INDICACAO => 'marrom'
        ], [1, 2, 3, 5]);
    }
}
