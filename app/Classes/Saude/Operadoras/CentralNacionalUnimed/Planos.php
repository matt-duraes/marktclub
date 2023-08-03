<?php

namespace App\Classes\Saude\Operadoras\CentralNacionalUnimed;

use App\Classes\Saude\Interface\PlanoInterface;
use Status\Status;

class Planos extends Status implements PlanoInterface
{
    public const ABSOLUTO = 'absoluto';
    public const CLASSICO_REGIONAL = 'classico_regional';
    public const ESTILO_NACIONAL = 'estilo_nacional';
    public const EXCLUSIVO_NACIONAL = 'exclusivo_nacional';
    public const SUPERIOR_NACIONAL = 'superior_nacional';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLASSICO_REGIONAL  => 'Clássico Regional',
            self::ESTILO_NACIONAL    => 'Estilo Nacional',
            self::ABSOLUTO           => 'Absoluto',
            self::SUPERIOR_NACIONAL  => 'Superior Nacional',
            self::EXCLUSIVO_NACIONAL => 'Exclusivo Nacional'
        ]);
    }
}
