<?php

namespace App\Classes\Demanda\Sprint;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const PUBLICADO = [1, 2];
    public const NOVA = 'nova';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDA_PRAZO = 'concluida-prazo';
    public const CONCLUIDA_ATRASADA = 'concluida-atrasada';
    public const CANCELADA = 'cancelada';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVA               => 'Nova',
            self::ANDAMENTO          => 'Andamento',
            self::CONCLUIDA_PRAZO    => 'Concluida no prazo',
            self::CONCLUIDA_ATRASADA => 'Concluida atrasada',
            self::CANCELADA          => 'Cancelada',
        ], [
            self::NOVA               => 'cinza',
            self::ANDAMENTO          => 'azul',
            self::CONCLUIDA_PRAZO    => 'verde',
            self::CONCLUIDA_ATRASADA => 'laranja',
            self::CANCELADA          => 'preto',
        ]);
    }
}
