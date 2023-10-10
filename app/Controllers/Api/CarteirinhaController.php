<?php

namespace App\Controllers\Api;

use App\Classes\Carteirinha\Helper;
use App\Controllers\Api\Trait\ClienteTrait;
use App\Models\Api\Carteirinha\CarteirinhaModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;
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
        $ClienteEntity = $this->pegarCliente($id, true);
        return mensagemSucesso(
            (new CarteirinhaModel($ClienteEntity))->pegarDados(),
            criptografar: Helper::CRIPTOGRAFAR
        );
    }
}
