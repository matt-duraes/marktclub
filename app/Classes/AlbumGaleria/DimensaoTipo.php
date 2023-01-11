<?php

namespace App\Classes\AlbumGaleria;

use Status\Status as StatusStatus;

final class DimensaoTipo extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'real' => 'Tamanho real',
                'fixo' => 'Tamanho fixo',
                'largura' => 'Largura máxima',
                'altura' => 'Altura máxima',
            ]
        );
    }
}
