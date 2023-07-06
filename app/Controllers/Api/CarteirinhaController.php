<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Trait\ClienteTrait;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use App\Models\Api\Carteirinha\CarteiraModel;

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
