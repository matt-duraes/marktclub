<?php

namespace App\Classes\Webview\Lista;

use Status\Status as StatusStatus;

final class Local extends StatusStatus
{
    public const GERAL = 'geral';
    public const SITE = 'site';
    public const APP = 'app';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::GERAL     => 'Geral',
            self::SITE      => 'Site',
            self::APP       => 'App',
        ]);
    }
}
