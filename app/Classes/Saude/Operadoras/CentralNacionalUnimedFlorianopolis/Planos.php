<?php

namespace App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis;

use App\Classes\Saude\Interface\PlanoInterface;
use Status\Status;

class Planos extends Status implements PlanoInterface
{
    public const REGIONAL = 'regional';
    public const ESTADUAL = 'estadual';
    public const NACIONAL = 'nacional';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::REGIONAL => 'Regional',
            self::ESTADUAL => 'Estadual',
            self::NACIONAL => 'Nacional'
        ]);
    }
}
