<?php

namespace App\Classes\SiteConfig;

use Status\Status as StatusStatus;

final class TemplateHeader extends StatusStatus
{
    public const PADRAO = 'padrao';
    public const UNAREG = 'unareg';
    public const BG = 'bg';
    public const IMAGEM = 'imagem';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PADRAO     => 'Padrão',
            self::UNAREG     => 'UNAREG',
            self::BG         => 'BG',
            self::IMAGEM     => 'Imagem',
        ]);
    }
}
