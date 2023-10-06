<?php

namespace App\Classes\Saude;

use Status\Status;

class Acomodacao extends Status
{
    public const ACOMODACAO_ENFERMARIA = 'enfermaria';
    public const ACOMODACAO_APARTAMENTO = 'apartamento';
    public const ACOMODACAO_ENFERMARIA_30 = 'enfermaria-30';
    public const ACOMODACAO_ENFERMARIA_50 = 'enfermaria-50';
    public const ACOMODACAO_BASICO = 'basico';
    public const ACOMODACAO_PRATICO = 'pratico';
    public const ACOMODACAO_VERSATIL = 'versatil';
    public const ACOMODACAO_COLETIVA = 'coletivo';
    public const ACOMODACAO_INDIVIDUAL = 'individual';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ACOMODACAO_ENFERMARIA      => 'Enfermaria',
            self::ACOMODACAO_APARTAMENTO     => 'Apartamento',
            self::ACOMODACAO_ENFERMARIA_30   => 'Enfermaria 30',
            self::ACOMODACAO_ENFERMARIA_50   => 'Enfermaria 50',
            self::ACOMODACAO_BASICO          => 'Básico',
            self::ACOMODACAO_PRATICO         => 'Prático',
            self::ACOMODACAO_VERSATIL        => 'Versátil',
            self::ACOMODACAO_COLETIVA        => 'Coletivo',
            self::ACOMODACAO_INDIVIDUAL      => 'Individual'
        ]);
    }
}
