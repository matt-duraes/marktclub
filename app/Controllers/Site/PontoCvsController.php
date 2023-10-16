<?php

namespace  App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\PontoCvs\BuscarModel;

final class PontoCvsController extends Controller
{
    public function index(): Response
    {
        $dadosCvs = (object)(new BuscarModel())->buscarDados();
        $erro = !empty($dadosCvs->erro) ? $dadosCvs->texto : '';
        $resultado = isset($dadosCvs->dado) ? $dadosCvs->dado : [];

        return view('ponto_cvs.index', [
            'r'    => $resultado,
            'erro' => $erro
        ]);
    }

    public function popupSolicitacao(): Response
    {
        return view('ponto_cvs.solicita_cvs');
    }

    public function postRealizarSolicitacao(Request $request): Response
    {
        return (new BuscarModel())->solicitarPontoCvs($request);
    }

    public function extrato(): Response
    {
        $extrato = (new BuscarModel())->extrato();
        return view('ponto_cvs.extrato_cvs', [
            'extrato' => $extrato
        ]);
    }
}
