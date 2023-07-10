<?php

namespace App\Models\Site\Automovel;

use stdClass;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): stdClass
    {
        $retorno = new stdClass();
        $retorno->tipo = $this->tipo;
        $retorno->lista = new stdClass();

        if ($lista instanceof stdClass && property_exists($lista, 'dado')) {
            $this->processarItens($lista->dado, $retorno->lista);
        }

        return $retorno;
    }
    private function processarItens($dado, &$retornoLista)
    {
        $rota = '';
        if($this->tipo == 'montadora') {
            $rota = route('automovel.veiculo');
        } elseif($this->tipo == 'modelo') {
            $rota = route('automovel.modelo');
        }

        foreach ($dado->lista as $key => $valor) {
            $retornoLista->$key = (object) [
                'id'       => $valor->id,
                'titulo'   => $valor->titulo,
                'texto'    => '',
                'link'    => $rota . '/' . $valor->url->valor,
                'imagem'   => $valor->imagem->link,
                'de'   => '',
                'por'   => '',
            ];
        }
    }


}
