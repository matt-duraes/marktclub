<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class CanalPreferencia extends StatusStatus
{
    public const WHATSAPP = 'whatsapp';
    public const EMAIL = 'email';
    public const TELEFONE = 'telefone';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::WHATSAPP => 'WhatsApp',
            self::EMAIL    => 'E-mail',
            self::TELEFONE => 'Telefone'
        ]);
    }
}
