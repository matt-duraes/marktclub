<?php

namespace App\Models\Site\Loja;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo' => 'loja',
            'lista' => [
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Nome do parceiro 01',
                    'link' => route('loja.detalhe') . '/loja',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Nome do parceiro 02',
                    'link' => route('loja.detalhe') . '/loja',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Nome do parceiro 03',
                    'link' => route('loja.detalhe') . '/loja',
                    'imagem' => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10% de desconto'
                ]
            ]
        ];
    }
}
