<?php

namespace App\Models\Site\Cinema;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class ListarModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'cinema',
            'lista' => [
                (object)[
                    'imagem'   => 'https://clube.marktclub.com.br/images/cinema/cinemark.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
                (object)[
                    'imagem'   => 'https://clube.marktclub.com.br/images/cinema/cinemaxx.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
                (object)[
                    'imagem'   => 'https://clube.marktclub.com.br/images/cinema/itau.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/cinema/playarte.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/cinema/uci.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/cinema/moviecom.png',
                    'link'     => 'https://afiliados.easylive.com.br/?aid=5'
                ],
            ]
        ];
    }
}
