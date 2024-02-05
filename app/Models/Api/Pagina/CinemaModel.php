<?php

namespace App\Models\Api\Pagina;

final class CinemaModel extends PaginaPadraoModel
{
    private string $linkArquivo = LINK_ARQUIVO . '/pagina/cinema';
    private string $link;

    public function __construct()
    {
        $this->link = 'https://afiliados.easylive.com.br/?aid=5';
        $this->sessao(function () {
            $this
                ->bannerDesktop(
                    imagem: $this->linkArquivo . '/banner_desktop.jpg',
                    link: $this->link,
                    target: self::TARGET_BLANK
                )
                ->bannerMobile(
                    imagem: $this->linkArquivo . '/banner_mobile.jpg',
                    link: $this->link,
                    target: self::TARGET_BLANK
                )
                ->observacao(
                    // @codingStandardsIgnoreStart
                    texto: 'Agora você pode comprar ingressos para o cinema com até 50% de desconto em vários cinemas do país'
                    // @codingStandardsIgnoreEnd
                )
                ->listaLogo(function () {
                    $this
                        ->logo(
                            imagem: $this->linkArquivo . '/cinemark.png',
                            titulo: 'Cinemark',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cinemaxx.png',
                            titulo: 'Cinemaxx',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/kinoplex.png',
                            titulo: 'Kinoplex',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/itau.png',
                            titulo: 'Itaú',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/playarte.png',
                            titulo: 'PlayArte',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/uci.png',
                            titulo: 'UCI',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/moviecom.png',
                            titulo: 'Moviecom',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cineart.png',
                            titulo: 'Cineart',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/topazio.png',
                            titulo: 'Topazio',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/arcoplex.png',
                            titulo: 'ArcoPlex',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cinepolis.png',
                            titulo: 'Cinepolis',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cinea.png',
                            titulo: 'Cinea',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cineplex.png',
                            titulo: 'Cineplex',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/circuito.png',
                            titulo: 'Circuito',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/gnc.png',
                            titulo: 'GNC',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                        ->logo(
                            imagem: $this->linkArquivo . '/cinesystem.png',
                            titulo: 'Cinesytem',
                            link: $this->link,
                            target: self::TARGET_BLANK
                        )
                    ;
                })
                ->botaoDestaque(texto: 'COMPRAR INGRESSO', link: $this->link, target: self::TARGET_BLANK);
        });
    }
}
