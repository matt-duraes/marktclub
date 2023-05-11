<?php

namespace App\Models\Site\SosMulher;

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
            'tipo'  => 'sosmulher',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => '180',
                    'link'   => '',
                    'imagem' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_180.png'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => '100',
                    'link'   => '',
                    'imagem' => 'https://clube.marktclub.com.br/images/denuncia_index_banner_100.png'
                ],
            ]
        ];
    }
}
