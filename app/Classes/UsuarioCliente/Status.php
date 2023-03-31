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
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ATIVO => 'Ativo',
                self::INATIVO => 'Inativo',
                self::BLOQUEADO => 'Bloqueado',
                self::INDICACAO => 'Indicação'
            ],
            cor: [
                self::ATIVO => 'verde',
                self::INATIVO => 'azul',
                self::BLOQUEADO => 'vermelho',
                self::INDICACAO => 'marrom'
            ],
            numero: [1, 2, 3, 5]
        );
    }
}
