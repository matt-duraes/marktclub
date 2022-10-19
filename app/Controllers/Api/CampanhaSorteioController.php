<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\CampanhaSorteio\SorteioEntity;
use App\Controllers\Api\Interface\BuscarInterface;

final class CampanhaSorteioController extends Controller implements BuscarInterface
{

    public function getBuscar(string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Sorteio = $this->pegarSorteio($id);

        return mensagemSucesso([
            'id' => $Sorteio->id,
            'titulo' => $Sorteio->titulo,
            'texto' => $Sorteio->texto,
            'imagem' => $Sorteio->imagem,
            'status' => $Sorteio->status->indice()
        ]);
    }

    public function postResultado(Request $request)
    {
        if (empty($request->id)) {
            mensagemStatus(404);
        }

        $Sorteio = $this->pegarSorteio($request->id);
        $Sorteio->sortearUsuarios();
        $Sorteio->salvar();

        return mensagemSucesso([
            'id' => $Sorteio->id,
            'sorteados' => $Sorteio->usuario_sorteado,
            'hash' => $Sorteio->hash
        ], status: 201);
    }

    public function getResultado(string $id, string $hash)
    {
        if (empty($id) || empty($hash)) {
            mensagemStatus(404);
        }

        $Sorteio = $this->pegarSorteio($id);
        if ($Sorteio->hash != $hash) {
            mensagemStatus(404, localhost: 'O hash do sorteio não é do ID informado.');
        }

        return mensagemSucesso([
            'id' => $Sorteio->id,
            'titulo' => $Sorteio->titulo,
            'texto' => $Sorteio->texto,
            'imagem' => $Sorteio->imagem,
            'sorteados' => $Sorteio->usuario_sorteado,
            'data' => $Sorteio->data_sorteio->data(),
            'status' => $Sorteio->status
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function pegarSorteio($registro): SorteioEntity
    {
        $Sorteio = new SorteioEntity();
        $Sorteio->id($registro);

        return $Sorteio;
    }
}
