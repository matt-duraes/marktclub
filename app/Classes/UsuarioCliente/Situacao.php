<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Situacao extends Status
{
    public const ATIVO = 'ativo';
    public const APOSENTADO = 'aposentado';
    public const PENSIONISTA = 'pensionista';
    public const CEDIDO = 'cedido';
    public const EXCEDENTE = 'excedente';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO       => 'Ativo',
            self::APOSENTADO  => 'Aposentado',
            self::PENSIONISTA => 'Pensionista',
            self::CEDIDO      => 'Cedido',
            self::EXCEDENTE   => 'Excedente'
        ]);
    }
}
