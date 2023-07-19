<?php

namespace App\Classes\ComercialEmpresa;

final class FinalidadePublica extends FinalidadeSecundaria
{
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            valor: $valor,
            lista: [
                self::ASSOCIACAO => 'Associação',
                self::SINDICATO  => 'Sindicato',
                self::EMBAIXADA  => 'Embaixada',
                self::CONSELHO   => 'Conselho de classe'
            ],
            numero: [1, 2, 3, 4]
        );
    }
}
