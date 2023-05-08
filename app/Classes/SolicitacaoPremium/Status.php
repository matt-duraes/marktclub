<?php

namespace App\Classes\SolicitacaoPremium;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const LIVRE = 'livre';
    public const GERADO = 'gerado';
    public const ESGOTADO = 'esgotado';
    public const ESTOURADO = 'estourado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LIVRE   => 'Voucher disponível',
            self::GERADO => 'Todos gerados',
            self::ESGOTADO  => 'Esgotado',
            self::ESTOURADO  => 'Estourado'
        ], [
            self::LIVRE   => 'verde',
            self::GERADO => 'azul',
            self::ESGOTADO  => 'preto',
            self::ESTOURADO  => 'vermelho'
        ]);
    }
}
