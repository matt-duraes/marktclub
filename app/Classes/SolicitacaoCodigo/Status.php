<?php

namespace App\Classes\SolicitacaoCodigo;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ABERTO = 'aberto';
    public const SOLICITADO = 'solicitado';
    public const VENCIDO = 'vencido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ABERTO => 'Aberto',
                self::SOLICITADO => 'Solicitado',
                self::VENCIDO => 'Vencido',
            ],
            cor: [
                self::ABERTO => 'azul',
                self::SOLICITADO => 'verde',
                self::VENCIDO => 'vermelho'
            ]
        );
    }
}
