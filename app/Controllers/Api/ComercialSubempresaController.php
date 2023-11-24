<?php

namespace App\Controllers\Api;

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;
use App\Models\Api\ComercialSubempresa\SelectModel;
use App\Models\Api\ComercialSubempresa\SubempresaEntity;
use App\Models\Api\ComercialSubempresa\SubempresaModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;

final class ComercialSubempresaController extends Controller implements
    ControllerSelectInterface,
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSelect(Request $request): Response
    {
        $Empresa = new SelectModel($request);
        return mensagemSucesso($Empresa->listarSelect());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $SubempresaEntity = new SubempresaEntity();
        $SubempresaEntity->uuid(
            $id,
            mensagem: 'Subempresa não encontrada ou inexistente'
        );
        return $this->retornoSucesso($SubempresaEntity);
    }

    /**
     * @param SubempresaEntity $subempresaEntity
     * @param int              $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(SubempresaEntity $subempresaEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $subempresaEntity,
                lista: [
                    'empresa', 'titulo', 'razao_social', 'nome_fantasia',
                    'responsavel_nome', 'cnpj', 'status', 'data_criacao',
                    'data_atualizacao'
                ]
            ),
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
        $SubempresaModel = new SubempresaModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Data($request->dataInicio),
            new Data($request->dataFinal),
            $request->titulo,
            $request->empresa,
            new Status($request->status)
        );
        return mensagemSucesso($SubempresaModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SubempresaEntity = new SubempresaEntity();
        $SubempresaEntity->set(lista: $request->dado());
        $SubempresaEntity->salvar();
        return $this->retornoSucesso($SubempresaEntity, 201);
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
        $SubempresaEntity = new SubempresaEntity();
        $SubempresaEntity->uuid(
            $id,
            mensagem: 'Subempresa não encontrada ou inexistente'
        );
        $SubempresaEntity->set(lista: $request->dado());
        $SubempresaEntity->salvar();
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
        $SubempresaEntity = new SubempresaEntity();
        $SubempresaEntity->uuid(
            $id,
            mensagem: 'Subempresa não encontrada ou inexistente'
        );
        $SubempresaEntity->destruir();
        return new Response(status: 204);
    }
}
