<?php

namespace App\Classes\Webview\Lista;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const BANNER = 'banner';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BANNER => 'Banner',
        ]);
    }
}
