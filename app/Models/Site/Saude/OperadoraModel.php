<?php

namespace App\Models\Site\Saude;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class OperadoraModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    public function listarDadosFederal(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => route('planosaude.unimedVitoria'),
                    'imagem' => 'https://clube.marktclub.com.br/images/logo_unimed_vitoria.jpg'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => route('planosaude.unimedSeguro'),
                    'imagem' => 'https://clube.marktclub.com.br/images/saude-unimed-seguro.png'
                ]
            ]
        ];
    }

    public function saudeBoleto(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => 'https://www.benevix.com.br/boletos/',
                    'imagem' => 'https://clube.marktclub.com.br/images/logo_unimed_vitoria.jpg'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => 'https://fenapef.admex.com.br/default.asp',
                    'imagem' => 'https://clube.marktclub.com.br/images/saude-unimed-seguro.png'
                ]
            ]
        ];
    }

    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Amil',
                    'link'   => route('planosaude.amil'),
                    'imagem' => 'https://clube.marktclub.com.br/images/logo_amil.png'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => route('planosaude.unimedVitoria'),
                    'imagem' => 'https://clube.marktclub.com.br/images/logo_unimed_vitoria.jpg'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Central Nacional Unimed',
                    'link'   => route('planosaude.centralnacional'),
                    'imagem' => 'https://clube.marktclub.com.br/images/cnu_logo.png'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed - Florianópolis',
                    'link'   => route('planosaude.unimedflorianopolis'),
                    'imagem' => 'https://clube.marktclub.com.br/images/logo_unimed_florianopolis.jpg'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => route('planosaude.unimedSeguro'),
                    'imagem' => 'https://clube.marktclub.com.br/images/saude-unimed-seguro.png'
                ]
            ]
        ];
    }
}
