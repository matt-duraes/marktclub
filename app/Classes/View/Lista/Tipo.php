<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const BLOCO = 'bloco';
    public const BANNER = 'banner';
    public const TITULO_TEXTO = 'titulo-texto';
    public const TITULO = 'titulo';
    public const SUBTITULO = 'subtitulo';
    public const BOTAO = 'botao';
    public const BOTAO_DESTAQUE = 'botao-destaque';
    public const CAMPANHA = 'campanha';
    public const LINHA = 'linha';
    public const RELACIONADO = 'relacionado';
    public const MARGEM = 'margem';
    public const TABELA = 'tabela';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BLOCO          => 'Bloco',
            self::BANNER         => 'Banner',
            self::TITULO_TEXTO   => 'Título e texto',
            self::TITULO         => 'Titulo',
            self::SUBTITULO      => 'Subtitulo',
            self::BOTAO          => 'Botão',
            self::BOTAO_DESTAQUE => 'Botão em destaque',
            self::CAMPANHA       => 'Campanha',
            self::LINHA          => 'Linha',
            self::RELACIONADO    => 'Relacionado',
            self::MARGEM         => 'Margem',
            self::TABELA         => 'Tabela',
        ]);
    }
}
