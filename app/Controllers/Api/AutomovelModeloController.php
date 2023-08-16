<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\Automovel\Modelo\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Automovel\Modelo\ModeloModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\Automovel\Modelo\ModeloEntity;
use System\Interface\ControllerAtualizarInterface;

final class AutomovelModeloController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->idSlug($id);

        return $this->retornoSucesso($Modelo);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Modelo = new ModeloModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            parceiro: $request->parceiro,
            status: new Status($request->status),
            ordem: new Ordem($request->ordem),
        );
        return mensagemSucesso($Modelo->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();

        return $this->retornoSucesso($Modelo, 201);
    }

    private function retornoSucesso(ModeloEntity $Modelo, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Modelo,
                lista: [
                    'titulo', 'procedimento', 'texto_procedimento', 'link_imagem', 'versao', 'url', 'status'
                ]
            ),
            status: $status,
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->destruir();

        return new Response(status: 204);
    }
}
