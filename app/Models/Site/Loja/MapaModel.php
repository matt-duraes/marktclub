<?php

namespace App\Models\Site\Loja;

use App\Classes\ParceiroLoja\Categoria;
use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

final class MapaModel
{
    private Categoria $Categoria;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        $this->url = route('loja.proxima');
        $this->Categoria = new Categoria($request->categoria);
        $this->requestLista();
    }

    /**
     * @throws Excecao
     */
    private function requestLista()
    {
        $Api = new ApiHelper('loja:listar');

        return $Api->body([
            'categoria' => $this->request->categoria,
            'pesquisa'  => $this->request->pesquisa,
            'latitude'  => $this->request->latitude,
            'longitude' => $this->request->longitude,
            'raio'      => $this->request->raio
        ])->post('/loja/mapa');
    }
}
