<?php

namespace App\Classes\Solicitacao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ENVIADO_EMPRESA = 'enviado-empresa';
    public const ENVIADO_USUARIO = 'enviado-usuario';
    public const PROBLEMA = 'problema';
    public const FINALIZADO = 'finalizado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO            => 'Novo',
            self::ENVIADO_EMPRESA => 'Enviado p/ Empresa',
            self::ENVIADO_USUARIO => 'Enviado p/ Usuário',
            self::PROBLEMA        => 'Problema',
            self::FINALIZADO      => 'Finalizado'
        ], [
            self::NOVO            => 'verde',
            self::ENVIADO_EMPRESA => 'amarelo',
            self::ENVIADO_USUARIO => 'laranja',
            self::PROBLEMA        => 'vermelho',
            self::FINALIZADO      => 'azul'
        ]);
    }
}
