<?php

namespace App\Classes\TextoClube;

use Status\Status;

final class Tipo extends Status
{
    public const FAQ = 'faq';
    public const COMO_FUNCIONA = 'como-funciona';
    public const GERAL = 'geral';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::FAQ           => 'FAQ',
            self::COMO_FUNCIONA => 'Como funciona',
            self::GERAL         => 'Geral',
        ]);
    }
}
