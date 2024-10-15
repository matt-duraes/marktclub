<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const BLOCO = 'bloco';
    public const DIV = 'div';
    public const RESTO = 'resto';
    public const BANNER = 'banner';
    public const TITULO_TEXTO = 'titulo-texto';
    public const TITULO = 'titulo';
    public const SUBTITULO = 'subtitulo';
    public const TEXTO = 'texto';
    public const BOTAO = 'botao';
    public const BOTAO_EMPRESA = 'botao-empresa';
    public const BOTAO_DESTAQUE = 'botao-destaque';
    public const BOTAO_FIXO = 'botao-fixo';
    public const CAMPANHA = 'campanha';
    public const LINHA = 'linha';
    public const RELACIONADO = 'relacionado';
    public const MARGEM = 'margem';
    public const TABELA = 'tabela';
    public const EDITOR = 'editor';
    public const LISTA = 'lista';
    public const IMAGEM = 'imagem';
    public const ICONE = 'icone';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BLOCO          => 'Bloco',
            self::DIV            => 'Div',
            self::RESTO          => 'Resto (grow)',
            self::BANNER         => 'Banner',
            self::TITULO_TEXTO   => 'Título e texto',
            self::TITULO         => 'Titulo',
            self::SUBTITULO      => 'Subtitulo',
            self::TEXTO          => 'Texto',
            self::BOTAO          => 'Botão',
            self::BOTAO_EMPRESA  => 'Botão empresa',
            self::BOTAO_DESTAQUE => 'Botão em destaque',
            self::BOTAO_FIXO     => 'Botão fixo',
            self::CAMPANHA       => 'Campanha',
            self::LINHA          => 'Linha',
            self::RELACIONADO    => 'Relacionado',
            self::MARGEM         => 'Margem',
            self::TABELA         => 'Tabela',
            self::EDITOR         => 'Editor',
            self::LISTA         => 'Lista',
            self::IMAGEM         => 'Imagem',
            self::ICONE         => 'Ícone',
        ]);
    }
}
