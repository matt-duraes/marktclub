<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Endereco\EnderecoModel;
use System\Interface\ControllerListarInterface;

final class EnderecoController extends Controller implements
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Endereco = new EnderecoModel($request);
        $dado = $Endereco->listarDados();

        return mensagemSucesso($dado);
    }
}
