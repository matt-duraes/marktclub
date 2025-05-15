<?php

namespace App\Classes\Saude\Operadoras\Unimed\Planos;

use App\Classes\Saude\Interface\PlanoInterface;
use Status\Status;

class Natal extends Status implements PlanoInterface
{
    public const ESSENCIAL_FLEX_1   = 'essencial_flex_1';
    public const ESSENCIAL_FLEX_2   = 'essencial_flex_2';
    public const GREEN_FLEX_1_AD_CE = 'green_flex_1_ad_ce';
    public const GREEN_FLEX_2_AD_CE = 'green_flex_2_ad_ce';
    public const GREEN_FLEX_1_AD_CA = 'green_flex_1_ad_ca';
    public const GREEN_FLEX_2_AD_CA = 'green_flex_2_ad_ca';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ESSENCIAL_FLEX_1   => 'Essencial Flex I Enfermagem',
            self::ESSENCIAL_FLEX_2   => 'Essencial Flex II Enfermagem',
            self::GREEN_FLEX_1_AD_CE => 'Green Flex I Enfermagem',
            self::GREEN_FLEX_2_AD_CE => 'Green Flex II Enfermagem',
            self::GREEN_FLEX_1_AD_CA => 'Green Flex I Apartamento',
            self::GREEN_FLEX_2_AD_CA => 'Green Flex II Apartamento',
        ]);
    }
}
