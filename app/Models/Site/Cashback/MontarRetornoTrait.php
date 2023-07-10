<?php

namespace App\Models\Site\Cashback;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo'  => 'cashback',
            'lista' => [
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Nome do parceiro 01',
                    'link'     => route('cashback.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '10%',
                    'tipo'     => 'cashback'
                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Nome do parceiro 02',
                    'link'     => route('cashback.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '4%',
                    'tipo'     => 'cashback'
                ],
                (object)[
                    'id'       => uuid(),
                    'titulo'   => 'Nome do parceiro 03',
                    'link'     => route('cashback.detalhe') . '/loja',
                    'imagem'   => 'https://arquivo.marktclub.com.br/parceiro/65c3d3b6716418d6425dfa858214a963.jpg',
                    'desconto' => '7%',
                    'tipo'     => 'cashback'
                ]
            ]
        ];
    }
}
