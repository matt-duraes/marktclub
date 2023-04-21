<?php

namespace Painel\ComercialProspeccao\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use Painel\ComercialProspeccao\Models\ProspeccaoModel;

final class ComercialProspeccaoController extends Controller
{
    public function lista()
    {
        $Prospeccao = new ProspeccaoModel();

        return view('painel.comercial_prospeccao.index', [
            'app' => 'comercial-prospeccao',
            'appTitulo' => 'Prospecção',
            'abordagem' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::ABORDAGEM),
            'apresentacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::APRESENTACAO),
            'negociacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::NEGOCIACAO),
            'avaliacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::AVALIACAO),
            'minuta' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::MINUTA)
        ]);
    }

    public function postAtualizarStatus(Request $request)
    {
        (new ApiHelper(token: true))
            ->validar('Erro ao mudar status do contrato, por favor, tente novamente.')
            ->body([
                'status' => $request->status
            ])
            ->put('/comercial-empresa/' . $request->id)
            ->object();

        return mensagemSucesso(['id' => $request->id], status: 201);
    }
    public function postAtualizarProspeccao(Request $request)
    {
        (new ApiHelper(token: true))
            ->validar('Erro ao mover contrato, por favor, tente novamente.')
            ->body([
                'prospeccao_status' => $request->prospeccao
            ])
            ->put('/comercial-empresa/' . $request->id)
            ->object();

        return mensagemSucesso(['id' => $request->id], status: 201);
    }
}
