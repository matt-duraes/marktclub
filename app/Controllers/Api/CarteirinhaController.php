<?php

namespace App\Controllers\Api;

use App\Classes\Carteirinha\Helper;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use Http\Response;
use System\Interface\ControllerBuscarInterface;

class CarteirinhaController implements ControllerBuscarInterface
{
    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ClienteEntity = new ClienteEntity();
        $ClienteEntity->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($ClienteEntity, lista: [
                'nome', 'matricula', 'cpf', 'documento_rg', 'numero_cartao',
                'aniversario', 'data_filiacao', 'data_validade', 'tipo', 'status'
            ]),
            200,
            Helper::CRIPTOGRAFAR
        );
    }
}
