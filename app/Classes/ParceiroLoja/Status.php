<?php

namespace App\Classes\ParceiroLoja;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const PROSPECCAO = 'prospeccao';
    public const PROBLEMA = 'problema';
    public const CANCELADO = 'cancelado';
    public const CONCLUIDO = 'concluido';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            lista: [
                self::PROSPECCAO => 'Prospecção',
                self::PROBLEMA   => 'Problema',
                self::CANCELADO  => 'Cancelado',
                self::CONCLUIDO  => 'Concluído',
            ],
            cor: [
                self::PROSPECCAO => 'azul',
                self::PROBLEMA   => 'vermelho',
                self::CANCELADO  => 'cinza',
                self::CONCLUIDO  => 'verde',
            ],
        );
    }
}
