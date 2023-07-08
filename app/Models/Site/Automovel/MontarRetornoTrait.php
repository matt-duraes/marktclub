<?php

namespace App\Models\Site\Automovel;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): stdClass
    {
        $retorno = new stdClass();
        $retorno->tipo = 'montadora';
        $retorno->lista = new stdClass();

        if ($lista instanceof stdClass && property_exists($lista, 'dado')) {
            $this->processarItens($lista->dado, $retorno->lista);
        }

        return $retorno;
    }
    private function processarItens($dado, &$retornoLista)
    {
        foreach ($dado->lista as $key => $valor) {
            $retornoLista->$key = (object) [
                'id'       => $valor->id,
                'titulo'   => $valor->titulo,
                'texto'    => '',
                'link'    => $valor->url->link,
                'imagem'   => $valor->imagem->logo
            ];
        }
    }


}
