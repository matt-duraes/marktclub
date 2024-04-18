<?php

namespace App\Classes\ParceiroLoja;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const PROSPECCAO = 'prospeccao';
    public const PROBLEMA = 'problema';
    public const CANCELADO = 'cancelado';
    public const CONCLUIDO = 'concluido';
    public const SEM_INTERESSE = 'sem-interesse';
    public const CLONADO = 'clonado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            lista: [
                self::PROSPECCAO     => 'Prospecção',
                self::PROBLEMA       => 'Problema',
                self::CANCELADO      => 'Cancelado',
                self::CONCLUIDO      => 'Concluído',
                self::SEM_INTERESSE  => 'Sem interesse',
                self::CLONADO        => 'Clonado',
            ],
            cor: [
                self::PROSPECCAO     => 'azul',
                self::PROBLEMA       => 'vermelho',
                self::CANCELADO      => 'cinza',
                self::CONCLUIDO      => 'verde',
                self::SEM_INTERESSE  => 'cinza',
                self::CLONADO        => 'cinza',
            ],
        );
    }
}
