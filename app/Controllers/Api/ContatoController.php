<?php

namespace App\Controllers\Api;

use App\Models\Api\Contato\ContatoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

class ContatoController extends Controller implements
    ControllerSalvarInterface
{
    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ContatoEntity = new ContatoEntity($request);
        $ContatoEntity->salvar();

        return $this->retornoSucesso($ContatoEntity, 201);
    }
}
