<?php

namespace App\Classes\PublicacaoNoticia;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const NOTICIA = 'noticia';
    public const ARTIGO = 'artigo';
    public const CARTILHA = 'cartilha';
    public const BLOG = 'blog';
    public const PAINEL = 'painel';
    public const EMPRESA = [
        'geral'  => [
            'lista'  => [
                self::NOTICIA => 'Notícias',
                self::ARTIGO  => 'Artigos',
            ],
            'numero' => [1, 2]
        ],
        'sinjutra' => [
            'lista'  => [
                self::NOTICIA   => 'Notícias',
                self::ARTIGO    => 'Artigos',
                self::CARTILHA  => 'Cartilha',
            ],
            'numero' => [1, 2, 3]
        ],
        'marktclub' => [
            'lista'  => [
                self::BLOG   => 'Blog',
                self::PAINEL => 'Painel',
            ],
            'numero' => [4, 5]
        ]
    ];

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
