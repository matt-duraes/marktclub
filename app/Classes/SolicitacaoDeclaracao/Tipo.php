<?php

namespace App\Classes\SolicitacaoDeclaracao;

use Status\Status;

class Tipo extends Status
{
    public const AUTOMOVEL = 'automovel';
    public const CONVENIO = 'convenio';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AUTOMOVEL => 'Automóvel',
            self::CONVENIO  => 'Convênio'
        ]);
    }
}
