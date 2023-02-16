<?php

namespace App\Classes\AlbumGaleria;

use Status\Status as StatusStatus;

final class DimensaoTipo extends StatusStatus
{
    const NOME = 'real';
    const FIXO = 'fixo';
    const LARGURA = 'largura';
    const ALTURA = 'altura';
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOME => 'Tamanho real',
                self::FIXO => 'Tamanho fixo',
                self::LARGURA => 'Largura máxima',
                self::ALTURA => 'Altura máxima',
            ]
        );
    }
}
