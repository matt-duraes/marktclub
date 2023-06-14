<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\EnqueteSatisfacao\EnqueteEntity;
use System\Interface\ControllerSalvarInterface;

final class EnqueteSatisfacaoController extends Controller implements
    ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        $Enquete = new EnqueteEntity();
        $Enquete->set(lista: $request->dado());
        $Enquete->salvar();

        return $this->retornoSucesso($Enquete, 201);
    }

    private function retornoSucesso(EnqueteEntity $Enquete, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Enquete,
                lista: [
                    'id', 'data_criacao', 'status'
                ],
            ),
            status: $status
        );
    }
}
