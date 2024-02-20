<?php

namespace System\Classes\Contato;

use Status\Status;

class Nome extends Status
{
    public const CELULAR = 'celular';
    public const RESIDENCIAL = 'residencial';
    public const WHATSAPP = 'whatsapp';
    public const FAX = 'fax';
    public const GRATUITO = 'gratuito';
    public const OUTRO = 'outro';
    public const EMAIL = 'email';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CELULAR     => 'Celular',
            self::RESIDENCIAL => 'Residencial',
            self::WHATSAPP    => 'WhatsApp',
            self::FAX         => 'Fax',
            self::GRATUITO    => 'Gratuito',
            self::OUTRO       => 'Outro',
            self::EMAIL       => 'E-mail',
        ]);
    }
}
