<?php

namespace App\Classes\SaudeSimulacao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ENVIADO = 'enviado';
    public const CANCELADO = 'cancelado';
    public const NOVA_SIMULACAO = 'nova-simulacao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO           => 'Novo',
            self::ENVIADO        => 'Enviado',
            self::CANCELADO      => 'Cancelado',
            self::NOVA_SIMULACAO => 'Nova Simulacao'
        ], [
            self::NOVO           => 'azul',
            self::ENVIADO        => 'verde',
            self::CANCELADO      => 'cinza',
            self::NOVA_SIMULACAO => 'cinza'
        ]);
    }
}
