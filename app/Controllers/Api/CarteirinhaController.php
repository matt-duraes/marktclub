<?php

namespace App\Controllers\Api;

use App\Classes\Carteirinha\Helper;
use App\Controllers\Api\Trait\ClienteTrait;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;
use System\Interface\ControllerBuscarInterface;

class CarteirinhaController extends Controller implements
    ControllerBuscarInterface
{
    use ClienteTrait;

    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ClienteEntity = $this->pegarCliente($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $ClienteEntity,
                lista: [
                    'nome', 'matricula', 'numero_cartao', 'documento',
                    'documento_rg', 'aniversario', 'data_filiacao', 'tipo', 'status'
                ]
            ),
            200,
            Helper::CRIPTOGRAFAR
        );
    }
}
