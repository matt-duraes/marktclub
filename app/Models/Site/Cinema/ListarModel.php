<?php

namespace App\Models\Site\Cinema;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class ListarModel extends ClubeApiHelper
{
    public function listarDados(): stdClass
    {
        return (object)[
            'tipo'  => 'cinema',
            'lista' => $this->pegarLista()
        ];
    }

    private function pegarLista(): array
    {
        return [
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cinemark.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cinemaxx.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/kinoplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/itau.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/playarte.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/uci.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/moviecom.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cineart.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/topazio.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/arcoplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cinepolis.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cinea.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cineplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/circuito.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/gnc.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => LINK_ARQUIVO . '/pagina/cinema/cinesystem.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ]
        ];
    }
}
