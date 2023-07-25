<?php

namespace Painel\ComercialProspeccao\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use Painel\ComercialProspeccao\Models\ProspeccaoModel;

final class ComercialProspeccaoController extends Controller
{
    public function lista()
    {
        $Prospeccao = new ProspeccaoModel();

        return view('comercial_prospeccao.index', [
            'app'             => 'comercial-prospeccao',
            'appTitulo'       => 'Prospecção',
            'abordagem'       => $Prospeccao->listar(ProspeccaoStatus::ABORDAGEM),
            'apresentacao'    => $Prospeccao->listar(ProspeccaoStatus::APRESENTACAO),
            'negociacao'      => $Prospeccao->listar(ProspeccaoStatus::NEGOCIACAO),
            'avaliacao'       => $Prospeccao->listar(ProspeccaoStatus::AVALIACAO),
            'minuta'          => $Prospeccao->listar(ProspeccaoStatus::MINUTA),
            'primeiro_status' => ProspeccaoStatus::ABORDAGEM,
            'ultimo_status'   => ProspeccaoStatus::MINUTA
        ]);
    }

    public function contato(string $id)
    {
        $Prospeccao = new ProspeccaoModel();
        $dado = $Prospeccao->buscar($id);
        return view('contato.index', [
            'id'      => $dado->id,
            'app'     => 'comercial-empresa',
            'contato' => [
                'Nome'     => $dado->responsavel_nome,
                'E-mail'   => $dado->responsavel_email,
                'Telefone' => $dado->responsavel_telefone,
            ]
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
