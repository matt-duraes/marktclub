<?php

namespace App\Controllers\Api\Votacao;

use Http\Request;
use Http\Response;
use Modules\Botao;
use Controller\Controller;
use App\Classes\Votacao\Dado\Status;
use App\Models\Api\Votacao\Dado\DadoModel;
use App\Models\Api\Votacao\Dado\DadoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\Votacao\Resultado\ResultadoModel;

final class DadoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Votacao = new DadoModel();
        $Votacao->set(lista: $request->dado());

        return mensagemSucesso($Votacao->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Votacao = new DadoEntity();
        $Votacao->idSlug($id);

        return $this->retornoPadrao(Votacao: $Votacao, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPost('texto', html: false);
        }
        $Votacao = new DadoEntity();
        $Votacao->set(lista: $dado);
        $Votacao->salvar();

        return $this->retornoPadrao(Votacao: $Votacao, status: 201);
    }

    private function retornoPadrao(DadoEntity $Votacao, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Votacao,
                lista: [
                    'titulo', 'texto', 'tipo', 'voto_unico', 'data_inicio', 'data_final',
                    'publicado', 'bloqueado', 'status_votacao', 'identificar_usuario', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }
        $Votacao = new DadoEntity();
        $Votacao->uuid($id);
        $Votacao->set(lista: $dado);
        $Votacao->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Votacao = new DadoEntity();
        $Votacao->uuid($id);
        $Votacao->destruir();

        return new Response(status: 204);
    }

    public function postCancelar(Request $request)
    {
        $Votacao = new DadoEntity();
        $Votacao->id($request->id);
        $Votacao->status = new Status(Status::CANCELADO);
        $Votacao->salvar();

        return mensagemSucesso([
            'id' => $Votacao->id
        ]);
    }

    public function postBloquear(Request $request)
    {
        $Votacao = new DadoEntity();
        $Votacao->id($request->id);
        $Votacao->bloqueado = new Botao(Botao::SIM);
        $Votacao->salvar();

        return mensagemSucesso([
            'id' => $Votacao->id
        ]);
    }

    public function postResultado(Request $request)
    {
        $Resultado = new ResultadoModel(id: $request->id);
        return mensagemSucesso($Resultado->retorno);
    }
}
