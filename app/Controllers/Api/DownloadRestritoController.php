<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\DownloadPrivado\DownloadEntity;

final class DownloadRestritoController extends Controller
{
    private DownloadEntity $Download;

    public function index(string $id)
    {
        $this->pegarArquivo($id);

        return view('download.restrito', [
            'nome' => $this->Download->nome->nome(),
            'id' => $id
        ]);
    }
    public function download(string $id, string $codigo)
    {
        $this->pegarArquivo($id);
        $this->Download->validarCodigoAutorizacao($codigo);
        return new Response(download: ROOT . '/files/arquivo_privado/download/' . $this->Download->arquivo);
    }

    /*
    |--------------------------------------------------------------------------
    | POSTS
    |--------------------------------------------------------------------------
    */
    public function postEmail(Request $request)
    {
        $this->pegarArquivo($request->id);
        $this->Download->gerarCodigoEmail();
        return new Response(status: 201);
    }
    public function postValidar(Request $request)
    {
        $this->pegarArquivo($request->id);
        $this->Download->validarCodigoEmail($request->codigo);

        return mensagemSucesso([
            'codigo' => $this->Download->codigo_autorizacao
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function pegarArquivo($id)
    {
        $this->Download = new DownloadEntity();
        $this->Download->id($id);
    }
}
