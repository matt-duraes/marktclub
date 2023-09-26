<?php

namespace App\Classes\ConstrutorClube;

use Status\Status as StatusStatus;

class TipoAtivacao extends StatusStatus
{
    public const CPF = 'cpf';
    public const MATRICULA = 'matricula';
    public const SIAPE = 'siape';
    public const EMAIL = 'email';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CPF       => 'CPF',
            self::MATRICULA => 'Matricula',
            self::SIAPE     => 'SIAPE',
            self::EMAIL     => 'E-mail'
        ]);
    }
}
