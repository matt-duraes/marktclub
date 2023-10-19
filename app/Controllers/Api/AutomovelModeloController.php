<?php

namespace App\Controllers\Api;

use App\Classes\Automovel\Modelo\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Automovel\Modelo\ModeloEntity;
use App\Models\Api\Automovel\Modelo\ModeloModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

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
     * @param ModeloEntity $Modelo
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ModeloEntity $Modelo, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Modelo, lista: [
                'parceiro', 'titulo', 'procedimento', 'texto_procedimento',
                'imagem', 'versao', 'data_inicio', 'data_final', 'url', 'status'
            ]),
            $status
        );
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
            publicado: new Botao($request->publicado),
            dataInicio: new Data($request->data_inicio),
            dataFinal: new Data($request->data_final),
            parceiro: $request->parceiro,
            status: new Status($request->status),
            ordem: new Ordem($request->ordem),
        );
        return mensagemSucesso($Modelo->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();
        return $this->retornoSucesso($Modelo, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->destruir();
        return new Response(status: 204);
    }
}
