<?php

namespace App\Models\Site\Saude;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class OperadoraModel extends ClubeApiHelper implements ListarInterface
{
    /**
     * @return stdClass
     */
    public function listarDados(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => $this->pegarListaAtiva()
        ];
    }

    private function pegarListaAtiva()
    {
        $lista = [
            (object)[
                'id'     => uuid(),
                'titulo' => 'Amil',
                'link'   => route('planosaude.amil'),
                'imagem' => LINK . '/images/site/logo_amil.png',
                'tipo'   => 'operadora',
                'status' => MENU_SAUDE_AMIL
            ],
            (object)[
                'id'     => uuid(),
                'titulo' => 'Unimed Vitória',
                'link'   => route('planosaude.unimedVitoria'),
                'imagem' => LINK . '/images/site/logo_unimed_vitoria.jpg',
                'tipo'   => 'operadora',
                'status' => MENU_SAUDE_VITORIA
            ],
            (object)[
                'id'     => uuid(),
                'titulo' => 'Central Nacional Unimed',
                'link'   => route('planosaude.centralnacional'),
                'imagem' => LINK . '/images/site/cnu_logo.png',
                'tipo'   => 'operadora',
                'status' => MENU_SAUDE_CNU
            ],
            (object)[
                'id'     => uuid(),
                'titulo' => 'Unimed - Florianópolis',
                'link'   => route('planosaude.unimedflorianopolis'),
                'imagem' => LINK . '/images/site/logo_unimed_florianopolis.jpg',
                'tipo'   => 'operadora',
                'status' => MENU_SAUDE_FLORIANOPOLIS
            ],
            (object)[
                'id'     => uuid(),
                'titulo' => 'Unimed Seguros',
                'link'   => route('planosaude.unimedSeguro'),
                'imagem' => LINK . '/images/site/saude-unimed-seguro.png',
                'tipo'   => 'operadora',
                'status' => MENU_SAUDE_SEGURO
            ]
        ];
        $retorno = [];
        foreach ($lista as $r) {
            if ($r->status != 1) {
                continue;
            }
            $retorno[] = $r;
        }
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function listarDadosFederal(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => route('planosaude.unimedVitoria'),
                    'imagem' => LINK . '/images/site/logo_unimed_vitoria.jpg',
                    'tipo'   => 'operadora'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => route('planosaude.unimedSeguro'),
                    'imagem' => LINK . '/images/site/saude-unimed-seguro.png',
                    'tipo'   => 'operadora'
                ]
            ]
        ];
    }

    /**
     * @return stdClass
     */
    public function saudeBoleto(): stdClass
    {
        return (object)[
            'tipo'  => 'operadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Vitória',
                    'link'   => 'https://www.benevix.com.br/boletos/',
                    'imagem' => LINK . '/images/site/logo_unimed_vitoria.jpg',
                    'tipo'   => 'operadora'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Unimed Seguros',
                    'link'   => 'https://fenapef.admex.com.br/default.asp',
                    'imagem' => LINK . '/images/site/saude-unimed-seguro.png',
                    'tipo'   => 'operadora'
                ]
            ]
        ];
    }
}
