<?php

namespace PainelController;

use Http\Request;
use Controller\Controller;

final class AtualizacaoController extends Controller
{
    public function getIndex(Request $request)
    {
        return mensagemSucesso([]);
    }

    public function getBuscar(string $url)
    {
        return mensagemSucesso([]);
    }
}
