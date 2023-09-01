<?php

namespace App\Models\Site\Cupom;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class BuscaModel extends ClubeApiHelper
{
    public function listarDados(string $url = null): stdClass
    {
        $dado = $this
            ->validar(status: 404, login: true)
            ->get('/parceiro-cupom/' . $url)
            ->object();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($lista): stdClass
    {
        $retorno = new stdClass();
        $retorno->tipo = 'cupom';
        $retorno->lista = new stdClass();

        if ($lista instanceof stdClass && property_exists($lista, 'dado')) {
            if (is_array($lista->dado)) {
                $this->processarItens($lista->dado, $retorno->lista);
            } else {
                $this->processarCasoNaoArray($lista->dado, $retorno->lista);
            }
        }

        return $retorno;
    }

    private function processarItens($dado, &$retornoLista)
    {
        foreach ($dado as $key => $valor) {
            $retornoLista->$key = (object) [
                'id'       => $valor->id,
                'titulo'   => $valor->parceiro->nome,
                'texto'    => $valor->descricao,
                'imagem'   => $valor->parceiro->imagem,
                'tipo'     => 'cupom',
                'validade' => $valor->validade
            ];
        }
    }

    private function processarCasoNaoArray($lista, &$retornoLista)
    {
        $retornoLista = (object) [
            'id'       => $lista->id,
            'titulo'   => $lista->parceiro->nome,
            'texto'    => $lista->descricao,
            'imagem'   => $lista->parceiro->imagem,
            'validade' => $lista->validade,
            'url'      => $lista->parceiro->link,
            'cupom'    => $lista->cupom,
            'tipo'     => $lista->tipo
        ];
    }
}
