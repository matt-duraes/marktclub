<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;

final class SprintController extends Controller
{
    private ApiHelper $Api;

    public function __construct()
    {
        parent::__construct();
        $this->Api = new ApiHelper(token: true);
    }

    public function postSprintAtiva()
    {
        $dado = $this->Api
            ->validar(status: 404)
            ->get('/demanda-sprint/ativa')
            ->object();

        if (!chaveExiste('dado.id', $dado)) {
            return mensagemStatus(404);
        }
        return mensagemSucesso($dado->dado);
    }

    public function postSprintSalvar(Request $request)
    {
        $dado = $this->Api
            ->validar(mensagem: 'Erro ao salvar sprint, por favor, tente novamente.')
            ->body([
                'titulo'      => $request->titulo,
                'data_inicio' => dataBanco($request->data_inicio),
                'data_final'  => dataBanco($request->data_final)
            ])
            ->post('/demanda-sprint')
            ->object();

        return mensagemSucesso($dado->dado, status: 201);
    }

    public function postDemandaAdd(Request $request)
    {
        $dado = $this->Api
            ->validar(mensagem: 'Erro ao adicionar demanda, por favor, tente novamente.')
            ->body([
                'demanda' => $request->demanda,
                'sprint'  => $request->sprint,
                'texto'   => $request->texto
            ])
            ->post('/demanda-sprint/demanda-adicionar')
            ->object();
        return mensagemSucesso(dado: ['id' => uuid()], status: 201);
    }

    public function postDemandaRemover(Request $request)
    {
        $dado = $this->Api
            ->validar(mensagem: 'Erro ao remover demanda, por favor, tente novamente.')
            ->body([
                'demanda' => $request->demanda,
                'sprint'  => $request->sprint,
                'texto'   => $request->texto
            ])
            ->post('/demanda-sprint/demanda-remover')
            ->object();
        return new Response(status: 204);
    }
}
