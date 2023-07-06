<?php

namespace App\Classes\Publicidade;

use Status\Status;

class Tipo extends Status
{
    public const PROMOCAO = 'promocao';
    public const AUTOMOVEL = 'automovel';
    public const PARCEIRO = 'parceiro';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PROMOCAO  => 'Promoção',
            self::AUTOMOVEL => 'Automóvel',
            self::PARCEIRO  => 'Parceiro'
        ]);
    }
}
