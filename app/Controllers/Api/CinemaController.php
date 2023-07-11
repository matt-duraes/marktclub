<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Trait\ClienteTrait;
use App\Models\Api\Cinema\ConsultaModel;
use App\Models\Api\Cinema\ExtratoModel;
use Erro\Excecao;
use Http\Response;

class CinemaController
{
    use ClienteTrait;

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function resgatarIngresso(string $id): Response
    {
        $cliente = $this->pegarCliente($id, true);

        if ($cliente->get('tipo') !== 1) {
            mensagemStatus(403);
        }

        $saldo = (new ConsultaModel())->consultaSaldo(
            $cliente->get('empresa'),
            $cliente->get('usuario')
        );

        return mensagemSucesso($saldo);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function solicitarExtrato(string $id): Response
    {
        $cliente = $this->pegarCliente($id, true);

        if ($cliente->get('tipo') !== 1) {
            mensagemStatus(403);
        }

        $extrato = (new ExtratoModel())->extrato(
            $cliente->get('empresa'),
            $cliente->get('usuario')
        );

        return mensagemSucesso($extrato);
    }
}
