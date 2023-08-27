<?php

namespace App\Classes\PublicacaoNoticia;

use Status\Status as StatusStatus;

class Local extends StatusStatus
{
    public const BANNER_PRINCIPAL = 'banner-principal';
    public const BANNER_SECUNDARIO = 'banner-secundario';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BANNER_PRINCIPAL  => 'Banner principal',
            self::BANNER_SECUNDARIO => 'Banner secundário',
        ]);
    }
}
