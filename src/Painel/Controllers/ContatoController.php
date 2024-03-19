<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use PainelModel\Contato\MontarUnicoModel;

final class ContatoController extends Controller
{
    public function postListarContato(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao listar contato, por favor, tente novamente.')
            ->json([
                'local_principal'  => $request->local_principal,
                'local_secundario' => $request->local_secundario,
                'pesquisa'         => $request->pesquisa,
                'pagina'           => $request->pagina,
                'quantidade'       => $request->quantidade,
            ])
            ->get('/contato')
            ->object();

        return mensagemSucesso($dado->dado);
    }

    public function postBuscarUnico(string $id)
    {
        $Contato = new MontarUnicoModel($id);
        return mensagemSucesso($Contato->contato);
    }

    public function postSalvarContato(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao salvar contato, por favor, tente novamente.')
            ->body($request->dado())
            ->post('/contato')
            ->object()->dado ?? [];

        return mensagemSucesso($dado, 201);
    }

    public function postAtualizarContato(Request $request, string $id)
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorreu um erro ao atualizar contato, por favor, tente novamente.')
            ->body($request->dado())
            ->put('/contato/' . $id)
            ->object();

        return new Response(status: 204);
    }

    public function postDeletarContato(string $id)
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorreu um erro ao deletar contato, por favor, tente novamente.')
            ->delete('/contato/' . $id)
            ->object();

        return new Response(status: 204);
    }
}
