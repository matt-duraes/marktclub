<?php

namespace App\Models\Site\Saude;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class PlanoModel extends ApiHelper implements ListarInterface
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
            'tipo'  => 'plano',
            'lista' => [
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Unimed Vitória',
                    'link'     => route('planosaude.unimedvitoria'),
                    'imagem'   => LINK_PADRAO . '/images/site/logo_unimed_vitoria.jpg',

                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Unimed Florianópolis',
                    'link'     => route('planosaude.unimedflorianopolis'),
                    'imagem'   => LINK_PADRAO . '/images/site/logo_unimed_florianopolis.jpg',

                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Amil',
                    'link'     => route('planosaude.amil'),
                    'imagem'   => LINK_PADRAO . '/images/site/logo_amil.png',

                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Central Nacional Unimed',
                    'link'     => route('planosaude.centralnacional'),
                    'imagem'   => LINK_PADRAO . '/images/site/logo_central_unimed.png',

                ],
            ]
        ];
    }
}
