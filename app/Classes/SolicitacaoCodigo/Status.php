<?php

namespace App\Classes\SolicitacaoCodigo;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ABERTO = 'aberto';
    public const SOLICITADO = 'solicitado';
    public const VENCIDO = 'vencido';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ABERTO     => 'Aberto',
            self::SOLICITADO => 'Solicitado',
            self::VENCIDO    => 'Vencido'
        ], [
            self::ABERTO     => 'azul',
            self::SOLICITADO => 'verde',
            self::VENCIDO    => 'vermelho'
        ]);
    }
}
