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
        $dado = (new BuscarModel())->buscarDados();
        return view('ponto_cvs.index', [
            'r' => $dado
        ]);
    }

    public function popupSolicitacao(): Response
    {
        return view('ponto_cvs.solicita_cvs');
    }

    public function postRealizarSolicitacao(Request $request)
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
