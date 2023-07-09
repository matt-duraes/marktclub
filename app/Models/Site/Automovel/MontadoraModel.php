<?php

namespace App\Models\Site\Automovel;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class MontadoraModel extends ClubeApiHelper implements ListarInterface
{
    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'montadora',
            'lista' => [
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Montadora 01',
                    'link'   => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'tipo'   => 'automovel'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Montadora 02',
                    'link'   => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'tipo'   => 'automovel'
                ],
                (object)[
                    'id'     => uuid(),
                    'titulo' => 'Montadora 03',
                    'link'   => route('automovel.veiculo') . '/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'tipo'   => 'automovel'
                ]
            ]
        ];
    }
}
