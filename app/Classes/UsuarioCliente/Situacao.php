<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Situacao extends Status
{
    const ATIVO = 'ativo';
    const APOSENTADO = 'aposentado';
    const PENSIONISTA = 'pensionista';
    const CEDIDO = 'cedido';
    const EXCEDENTE = 'excedente';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ATIVO => 'Ativo',
                self::APOSENTADO => 'Aposentado',
                self::PENSIONISTA => 'Pensionista',
                self::CEDIDO => 'Cedido',
                self::EXCEDENTE => 'Excedente'
            ]
        );
    }
}
