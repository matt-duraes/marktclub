<?php

namespace App\models\Site\Cupom;

use stdClass;
use Helpers\ApiHelper;
use App\Models\Site\ListarInterface;

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
            'tipo' => 'cupom',
            'lista' => [
                (object)[
                    'id' => '1ff40f68-f67f-4553-91d8-73366e24e63f',
                    'titulo' => 'Nome do parceiro 01',
                    'texto' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'validade' => '10/10/2025'
                ],
                (object)[
                    'id' => 'c65434a9-1520-4f48-95bd-2ac1ae818b81',
                    'titulo' => 'Nome do parceiro 02',
                    'texto' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'validade' => '10/10/2025'
                ],
                (object)[
                    'id' => '7ec36ca0-ed47-4b09-8c90-774a0db68f85',
                    'titulo' => 'Nome do parceiro 03',
                    'texto' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'validade' => '10/10/2025'
                ],
            ]
        ];
    }
}
