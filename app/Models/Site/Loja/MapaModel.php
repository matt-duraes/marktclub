<?php

namespace App\Models\Site\Loja;

use Http\Request;
use Helpers\ApiHelper;
use App\Classes\Loja\Categoria;

final class MapaModel
{
    private Categoria $Categoria;

    public function __construct(
        protected Request $request
    ) {
        $this->url = route('loja.proxima');
        $this->Categoria = new Categoria($request->categoria);

        $this->requestLista();
    }

    private function requestLista()
    {
        $Api = new ApiHelper('loja:listar');

        $listaApi = $Api->body([
            'categoria' => $this->request->categoria,
            'pesquisa' => $this->request->pesquisa,
            'latitude' => $this->request->latitude,
            'longitude' => $this->request->longitude,
            'raio' => $this->request->raio
        ])->post('/loja/mapa');


        return $listaApi;

    }

}
