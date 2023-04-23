<?php

namespace App\models\Site\Automovel;

use stdClass;
use Helpers\ApiHelper;
use App\Models\Site\ListarInterface;

final class MontadoraModel extends ApiHelper implements ListarInterface
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
            'tipo' => 'montadora',
            'lista' => [
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Montadora 01',
                    'link' => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Montadora 02',
                    'link' => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Montadora 03',
                    'link' => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg'
                ],
            ]
        ];
    }
}
