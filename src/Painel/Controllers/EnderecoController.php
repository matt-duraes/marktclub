<?php

namespace PainelController;

use Http\Request;
use Controller\Controller;
use Helpers\LocalizacaoHelper;

final class EnderecoController extends Controller
{
    public function postBuscarGeolocalizacao(Request $request)
    {
        $Localizacao = new LocalizacaoHelper;
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
        $Localizacao = new LocalizacaoHelper;
        $dado = $Localizacao->pegarEnderecoPeloCep($request->cep);

        return mensagemSucesso($dado);
    }

    public function postBuscarCidade(Request $request)
    {
        $Localizacao = new LocalizacaoHelper;
        $dado = $Localizacao->pegarListaCidadePeloEstado($request->estado, titulo: 'Escolha uma cidade');

        return mensagemSucesso($dado);
    }
}
