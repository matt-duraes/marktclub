<?php

namespace App\Classes\PublicacaoNoticia;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const NOTICIA = 'noticia';
    public const ARTIGO = 'artigo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOTICIA => 'Notícia',
            self::ARTIGO  => 'Artigo',
        ]);
    }
}
