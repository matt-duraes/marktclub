<?php

namespace App\Classes\SiteConfig;

use Status\Status as StatusStatus;

final class TemplateFooter extends StatusStatus
{
    public const PADRAO = 'padrao';
    public const BG = 'bg';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PADRAO => 'Padrão',
            self::BG     => 'BG',
        ]);
    }
}
