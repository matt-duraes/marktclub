<?php

namespace ApiController;

use ApiModel\Contato\ContatoModel;
use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Classes\Contato\Local;
use System\Classes\Contato\Nome;
use System\Classes\Contato\Tipo;
use System\Interface\ControllerListarInterface;

final class ContatoController extends Controller implements
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Contato = new ContatoModel(
            vinculo: $request->vinculo,
            local: new Local($request->local),
            tipo: new Tipo($request->tipo),
            nome: new Nome($request->nome),
        );
        return mensagemSucesso($Contato->listarDados());
    }
}
