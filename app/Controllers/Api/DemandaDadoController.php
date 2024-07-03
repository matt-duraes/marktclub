<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Helpers\OrmHelper;
use Controller\Controller;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\DemandaModel;
use App\Models\Api\Demanda\DemandaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use System\Interface\ControllerAtualizarInterface;

final class DemandaDadoController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Demanda = new DemandaModel(
            new Status($request->status),
            new Ordem($request->ordem),
            new Area($request->area),
            new Tipo($request->tipo),
            $request->tarefa_tipo,
            $request->empresa,
            $request->equipe,
            $request->data_inicio,
            $request->data_fim,
        );

        return mensagemSucesso($Demanda->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Demanda = new DemandaEntity();
        $Demanda->uuid($id);

        return $this->retornoPadrao($Demanda);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);
        $Demanda = new DemandaEntity();
        $Demanda->set(lista: $dado);
        $Demanda->salvar();

        return $this->retornoPadrao($Demanda, 201);
    }

    private function retornoPadrao(DemandaEntity $Demanda, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Demanda,
                lista: [
                    'area', 'titulo', 'texto', 'empresa', 'dono', 'equipe', 'seguindo', 'estou_seguindo',
                    'com_prazo', 'data_entrega', 'sou_dono', 'sou_dev', 'tarefa', 'data_criacao', 'arquivo', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if ($request->existe('id_admin_empresa')) {
            $Empresa = new EmpresaEntity();
            $Empresa->uuid($request->id_admin_empresa);
            $dado['id_admin_empresa'] = $Empresa->get('id');
        }
        if ($request->existe('id_usuario_equipe')) {
            $dado['id_usuario_equipe'] = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($request->id_usuario_equipe);
        }
        if ($request->existe('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }

        $Demanda = new DemandaEntity();
        $Demanda->uuid($id);
        $Demanda->set(lista: $dado);
        $Demanda->salvar();

        return new Response(status: 204);
    }

    public function postSeguir(string $id)
    {
        $Demanda = new DemandaEntity();
        $Demanda->uuid($id);
        $Demanda->seguir();
        $Demanda->salvar();
        return mensagemSucesso(['id' => uuid()], status: 201);
    }

    public function deleteSeguir(string $id)
    {
        $Demanda = new DemandaEntity();
        $Demanda->uuid($id);
        $Demanda->seguirParar();
        $Demanda->salvar();
        return new Response(status: 204);
    }

    public function postCancelar(Request $request, string $id)
    {
        $request->vazio('motivo', mensagem: 'O campo motivo é obrigatório.');

        $Demanda = new DemandaEntity();
        $Demanda->uuid($id);
        $Demanda->cancelar($request->motivo);

        return new Response(status: 204);
    }
}
