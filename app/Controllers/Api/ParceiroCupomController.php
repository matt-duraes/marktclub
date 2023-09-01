<?php

namespace App\Controllers\Api;

use App\Models\Api\ParceiroCupom\CupomEntity;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ParceiroCupom\CupomModel;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

final class ParceiroCupomController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $CupomHelper = new CupomModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade)
        );
        $listar = $CupomHelper->listarDados();

        return mensagemSucesso($listar);
    }

    public function getBuscar(string $id): Response
    {
        $Cupom = new CupomEntity();
        $Cupom->uuid($id);

        return $this->retornoSucesso($Cupom);
    }

    private function retornoSucesso(CupomEntity $Cupom): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Cupom,
                lista: ['parceiro', 'descricao', 'cupom', 'desconto', 'categoria', 'link', 'validade', 'auditado']
            )
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Cupom = new CupomEntity();

        $Cupom->uuid($id);
        $Cupom->set(lista: $request->dado());
        $Cupom->salvar();

        return new Response(status: 204);
    }
}
