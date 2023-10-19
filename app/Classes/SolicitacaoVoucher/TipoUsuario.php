<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class TipoUsuario extends Status
{
    public const TITULAR = 'titular';
    public const DEPENDENTE = 'dependente';
    public const FUNCIONARIO = 'funcionario';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::TITULAR     => 'Titular',
            self::DEPENDENTE  => 'Dependente',
            self::FUNCIONARIO => 'Funcionario'
        ]);
    }
}
