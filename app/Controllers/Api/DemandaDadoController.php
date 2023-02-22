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
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
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
    public function getBuscar(string $id): Response
    {
        $Demanda = new DemandaEntity();
        $Demanda->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Demanda,
                lista: [
                    'titulo', 'empresa', 'dono', 'equipe', 'seguindo', 'estou_seguindo',
                    'com_prazo', 'data_entrega', 'sou_dono', 'sou_dev', 'tarefa', 'arquivo', 'status'
                ]
            )
        );
    }

    public function getListar(Request $request): Response
    {
        $Demanda = new DemandaModel(
            new Status($request->status),
            new Ordem($request->ordem)
        );

        return mensagemSucesso($Demanda->listarDados());
    }

    public function postSalvar(Request $request): Response
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

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if ($request->existe('id_admin_empresa')) {
            $Empresa = new EmpresaEntity();
            $Empresa->id($request->id_admin_empresa);
            $dado['id_admin_empresa'] = $Empresa->get('id');
        }
        if ($request->existe('id_usuario_equipe')) {
            $Equipe = new EquipeEntity(validarToken: false);
            $Equipe->id($request->id_usuario_equipe);
            $dado['id_usuario_equipe'] = $Equipe->get('id');
        }

        $Demanda = new DemandaEntity();
        $Demanda->id($id);
        $Demanda->set(lista: $dado);
        $Demanda->salvar();

        return new Response(status: 204);
    }
}
