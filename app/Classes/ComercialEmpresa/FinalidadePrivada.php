<?php

namespace App\Classes\ComercialEmpresa;

final class FinalidadePrivada extends FinalidadeSecundaria
{
    public const FACULDADE = 'faculdade';
    public const BANCO = 'banco';
    public const OUTRO = 'outro';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            valor: $valor,
            lista: [
                self::FACULDADE  => 'Faculdade',
                self::BANCO      => 'Banco',
                self::OUTRO      => 'Outro'
            ],
            numero: [5,6,7]
        );
    }
}
