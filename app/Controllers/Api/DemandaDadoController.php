<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\DemandaModel;
use App\Models\Api\Demanda\DemandaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;

final class DemandaDadoController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getBuscar(string $id)
    {
        $Demanda = new DemandaEntity();
        $Demanda->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Demanda,
                lista: [
                    'titulo', 'empresa', 'dono', 'equipe', 'seguindo', 'estou_seguindo',
                    'sou_dono', 'sou_dev', 'tarefa', 'arquivo'
                ]
            )
        );
    }

    public function getListar(Request $request)
    {
        $Demanda = new DemandaModel(
            new Status($request->status),
            new Ordem($request->ordem)
        );

        return mensagemSucesso($Demanda->listarDados());
    }

    public function postSalvar(Request $request)
    {
        $Demanda = new DemandaEntity(
            titulo: $request->titulo,
            empresa: $request->empresa,
            tipo: new Tipo($request->tipo)
        );
        $Demanda->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Demanda,
                lista: ['id', 'titulo', 'data_criacao', 'status']
            ),
            201
        );
    }

    public function atualizar(string $id)
    {
        ppe($id);
    }
    public function putAtualizar(Request $request, string $id)
    {
        $Demanda = new DemandaEntity();
        $Demanda->id($id);
        $Demanda->set(lista: $request->dado());
        $Demanda->salvar();

        return new Response(status: 204);
    }
}
