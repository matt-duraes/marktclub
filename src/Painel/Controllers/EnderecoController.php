<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use Helpers\LocalizacaoHelper;

final class EnderecoController extends Controller
{
    public function postBuscarGeolocalizacao(Request $request)
    {
        $Localizacao = new LocalizacaoHelper();
        $dado = $Localizacao->pegarGeolocalizacaoPeloEndereco(
            pais: $request->pais,
            titulo: $request->titulo,
            cep: $request->cep,
            logradouro: $request->logradouro,
            numero: $request->numero,
            bairro: $request->bairro,
            cidade: $request->cidade,
            estado: $request->estado
        );

        return mensagemSucesso($dado);
    }

    public function postBuscarEnderecoPeloCep(Request $request)
    {
        $Localizacao = new LocalizacaoHelper();
        $dado = $Localizacao->pegarEnderecoPeloCep($request->cep);

        return mensagemSucesso($dado);
    }

    public function postBuscarCidade(Request $request)
    {
        $Localizacao = new LocalizacaoHelper();
        $dado = $Localizacao->pegarListaCidadePeloEstado($request->estado, titulo: 'Escolha uma cidade');

        return mensagemSucesso($dado);
    }

    public function postListarEndereco(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao listar endereço, por favor, tente novamente.')
            ->json([
                'tabela'     => $request->tabela,
                'local'      => $request->local,
                'vinculo'    => $request->id,
                'pagina'     => $request->pagina,
                'quantidade' => $request->quantidade,
                'pais'       => $request->pais,
                'estado'     => $request->estado,
                'titulo'     => $request->titulo
            ])
            ->get('/endereco')
            ->object();

        return mensagemSucesso($dado);
    }

    public function postSalvarEndereco(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorreu um erro ao salvar endereço, por favor, tente novamente.')
            ->json($request->dado())
            ->post('/endereco')
            ->object();

        return new Response(status: 204);
    }

    public function postAtualizarEndereco(Request $request, string $id)
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorreu um erro ao salvar endereço, por favor, tente novamente.')
            ->json($request->dado())
            ->post('/endereco/' . $id)
            ->object();

        return new Response(status: 204);
    }
}
