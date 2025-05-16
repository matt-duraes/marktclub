<?php

namespace App\Classes\Saude\Operadoras\Unimed\Planos;

use App\Classes\Saude\Interface\PlanoInterface;
use Status\Status;

class Jundiai extends Status implements PlanoInterface
{
    public const FLEX_IDEAL     = 'flex-ideal';
    public const FLEX_PLUS      = 'flex-plus';
    public const CLASSICO_IDEAL = 'classico-ideal';
    public const CLASSICO_PLUS  = 'classico-plus';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::FLEX_IDEAL     => 'Flex Ideal',
            self::FLEX_PLUS      => 'Flex Plus',
            self::CLASSICO_IDEAL => 'Clássico Ideal',
            self::CLASSICO_PLUS  => 'Clássico Plus',
        ]);
    }
}
