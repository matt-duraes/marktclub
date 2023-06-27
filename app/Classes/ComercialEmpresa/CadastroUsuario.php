<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class CadastroUsuario extends StatusStatus
{
    public const API = 'api';
    public const CLIENTE = 'cliente';
    public const COMERCIAL = 'comercial';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::API => 'Via API',
            self::CLIENTE => 'Pelo cliente via painel',
            self::COMERCIAL => 'Pelo comercial via painel'
        ]);
    }
}
