<?php

namespace App\Models\Api\Pagina;

final class TurismoModel extends PaginaPadraoModel
{
    private string $linkArquivo = LINK_ARQUIVO . '/pagina/turismo';
    private string $link = LINK_API . '/turismo/redirecionar/{usuario}';

    public function __construct()
    {
        $this
            ->sessao(function () {
                $this
                    ->bannerDesktop(
                        imagem: $this->linkArquivo . '/banner_desktop.png',
                        link: $this->link,
                        target: self::TARGET_BLANK
                    )
                    ->bannerMobile(
                        imagem: $this->linkArquivo . '/banner_mobile.png',
                        link: $this->link,
                        target: self::TARGET_BLANK
                    )
                    ->titulo('123 Milhas com 5% de desconto')
                    ->texto('Agora você tem desconto no maior portal de vendas de passagem aéreas e hotéis do Brasil')
                    ->botao(
                        link: $this->link,
                        texto: 'ACESSAR SITE',
                        target: self::TARGET_BLANK
                    );
            });
    }
}
