<?php

namespace ApiController;

use Http\Request;
use Controller\Controller;
use ApiModel\Upload\ArquivoModel;
use App\Controllers\Api\Interface\ListarInterface;

final class UploadArquivoController extends Controller implements
    ListarInterface
{
    public function getListar(Request $request)
    {
        $Arquivo = new ArquivoModel();
        $lista = $Arquivo->buscarArquivos($request->pagina, $request->pesquisa, $request->grupo);

        return mensagemSucesso($lista);
    }
}
