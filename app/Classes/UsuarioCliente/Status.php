<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_ATIVO = 'ativo';
    const STATUS_INATIVO = 'inativo';
    const STATUS_BLOQUEADO = 'bloqueado';
    const STATUS_INDICACAO = 'indicacao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_ATIVO => 'Ativo',
                self::STATUS_INATIVO => 'Inativo',
                self::STATUS_BLOQUEADO => 'Bloqueado',
                self::STATUS_INDICACAO => 'Indicação'
            ],
            cor: [
                self::STATUS_ATIVO => 'verde',
                self::STATUS_INATIVO => 'azul',
                self::STATUS_BLOQUEADO => 'vermelho',
                self::STATUS_INDICACAO => 'marrom'
            ],
            numero: [1, 2, 3, 5]
        );
    }
}
