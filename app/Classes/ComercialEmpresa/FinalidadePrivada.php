<?php

namespace App\Classes\ComercialEmpresa;

final class FinalidadePrivada extends FinalidadeSecundaria
{
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            valor: $valor,
            lista: [
                self::FACULDADE          => 'Faculdade',
                self::BANCO              => 'Banco',
                self::COOPERATIVA        => 'Cooperativa',
                self::ASSOCIACAO_PRIVADA => 'Associação Privada',
                self::OUTRO              => 'Outro'
            ],
            numero: [5, 6, 7, 8, 9]
        );
    }
}
