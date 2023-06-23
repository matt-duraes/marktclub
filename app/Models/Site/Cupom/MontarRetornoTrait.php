<?php

namespace App\Models\Site\Cupom;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): stdClass
    {

        $retorno = (object)[
            'tipo' => 'cupom',
            'lista' => (object)[]
        ];

        if (is_object($lista) && !empty($lista->dado)) {
            foreach ($lista->dado as $key => $valor) {
                $retorno->lista->$key = (object)[
                    'id'       => $valor->id,
                    'titulo'   => $valor->parceiro->nome,
                    'texto'    => $valor->descricao,
                    'imagem'   => $valor->parceiro->imagem,
                    'validade' => $valor->validade
                ];
            }
        }

        return $retorno ;
    }
}
