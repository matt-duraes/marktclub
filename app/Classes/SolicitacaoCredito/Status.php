<?php

namespace App\Classes\SolicitacaoCredito;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ENVIADO_PARCEIRO = 'enviado-parceiro';
    public const CONTRATADO = 'contratado';
    public const CANCELADO = 'cancelado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO             => 'Novo',
            self::ENVIADO_PARCEIRO => 'Enviado p/ Parceiro',
            self::CONTRATADO       => 'Contratado',
            self::CANCELADO        => 'Cancelado'
        ], [
            self::NOVO             => 'azul',
            self::ENVIADO_PARCEIRO => 'amarelo',
            self::CONTRATADO       => 'verde',
            self::CANCELADO        => 'vermelho'
        ]);
    }
}
