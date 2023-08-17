<?php

namespace App\Classes\SolicitacaoCredito;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ENVIADO_PARCEIRO = 'enviado-parceiro';
    public const CONTRATADO = 'contratado';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO             => 'Novo',
            self::ENVIADO_PARCEIRO => 'Enviado para parceiro',
            self::CONTRATADO       => 'Contratado',
            self::CANCELADO        => 'Cancelado'
        ]);
    }
}
