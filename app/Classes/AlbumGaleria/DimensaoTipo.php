<?php

namespace App\Classes\AlbumGaleria;

use Status\Status as StatusStatus;

final class DimensaoTipo extends StatusStatus
{
    public const NOME = 'real';
    public const FIXO = 'fixo';
    public const LARGURA = 'largura';
    public const ALTURA = 'altura';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOME    => 'Tamanho real',
            self::FIXO    => 'Tamanho fixo',
            self::LARGURA => 'Largura máxima',
            self::ALTURA  => 'Altura máxima'
        ]);
    }
}
