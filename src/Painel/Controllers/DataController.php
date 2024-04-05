<?php

namespace PainelController;

use Http\Request;
use Controller\Controller;
use PainelModel\Data\MontarListaModel;

final class DataController extends Controller
{
    public function postListar(Request $request)
    {
        $Lista = new MontarListaModel(
            local_principal: $request->local_principal,
            vinculo: $request->vinculo,
            pagina: $request->pagina
        );

        return mensagemSucesso($Lista->retorno);
    }
}
