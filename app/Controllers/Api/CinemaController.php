<?php

namespace App\Controllers\Api;

use App\Models\Api\Cinema\CinemaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use Http\Response;

class CinemaController
{
    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function resgatarIngresso(string $id): Response
    {
        $cliente = new ClienteEntity();
        $cliente->uuid($id);

        if ($cliente->get('tipo') !== 1) {
            mensagemStatus(403);
        }

        $saldo = (new CinemaEntity())->consultaSaldo(
            $cliente->get('empresa'),
            $cliente->get('usuario')
        );

        return mensagemSucesso($saldo);
    }

    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function solicitarExtrato(string $id): Response
    {
        $cliente = new ClienteEntity();
        $cliente->uuid($id);

        if ($cliente->get('tipo') !== 1) {
            mensagemStatus(403);
        }

        $extrato = (new CinemaEntity())->extrato(
            $cliente->get('empresa'),
            $cliente->get('usuario')
        );

        return mensagemSucesso($extrato);
    }
}
