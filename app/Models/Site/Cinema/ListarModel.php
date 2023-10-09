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
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cinemark.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cinemaxx.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/kinoplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/itau.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/playarte.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/uci.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/moviecom.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cineart.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/topazio.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/arcoplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cinepolis.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cinea.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cineplex.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/circuito.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/gnc.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ],
            (object)[
                'imagem' => 'https://arquivo.markt.club/pagina/cinema/cinesystem.png',
                'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86',
            ]
        ];
    }
}
