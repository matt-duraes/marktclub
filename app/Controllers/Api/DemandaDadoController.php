<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\DemandaModel;
use App\Models\Api\Demanda\DemandaEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;

final class DemandaDadoController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface
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
                    'sou_dono', 'sou_dev', 'tarefa'
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
}
