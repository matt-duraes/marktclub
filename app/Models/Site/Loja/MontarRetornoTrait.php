<?php

namespace App\Models\Site\Loja;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'loja',
            'lista' => [
                (object)[
                    'id'       => 'a740333e-b624-4a3d-b388-1e0bb1e7d586',
                    'titulo'   => 'Nome do parceiro 01',
                    'link'     => route('loja.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto',
                    'favorito' => '0'
                ],
                (object)[
                    'id'       => 'abe97655-2422-44e5-9159-1a7b0b0ce7f7',
                    'titulo'   => 'Nome do parceiro 02',
                    'link'     => route('loja.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto',
                    'favorito' => '1'
                ],
                (object)[
                    'id'       => 'a740333e-b624-4a3d-b388-1e0bb1e7d586',
                    'titulo'   => 'Nome do parceiro 03',
                    'link'     => route('loja.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto',
                    'favorito' => '1'
                ]
            ]
        ];
    }
}
