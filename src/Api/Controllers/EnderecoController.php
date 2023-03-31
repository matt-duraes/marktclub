<?php

namespace ApiController;

use ApiModel\Endereco\EnderecoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerListarInterface;

final class EnderecoController extends Controller implements
    ControllerListarInterface
{
    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Endereco = new EnderecoModel($request);
        $dado = $Endereco->listarDados();

        return mensagemSucesso($dado);
    }
}
