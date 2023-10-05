<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Controllers\Api\Trait\ClienteTrait;
use App\Models\Api\Carteirinha\CarteiraModel;
use System\Interface\ControllerBuscarInterface;

class CarteirinhaController extends Controller implements
    ControllerBuscarInterface
{
    use ClienteTrait;

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $ClienteEntity = $this->pegarCliente($id, true);

        $Carteira = (new CarteiraModel($ClienteEntity))->pegarCarteirinha();

        return mensagemSucesso($Carteira);
    }
}
