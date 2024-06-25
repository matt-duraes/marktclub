<?php

namespace PainelApp\assinatura\Controllers;

use Http\Request;
use Controller\Controller;

final class AssinaturaController extends Controller
{
    public function index()
    {
        return view('painel.assinatura.index');
    }

    public function html(Request $request)
    {
        $nome = $request->nome;
        if (!$request->vazio('cargoSigla') && !$request->vazio('cargoNome')) {
            $nome .= ' | <b style="color: #ffd700;">' . $request->cargoSigla . '</b>' . $request->cargoNome;
        } elseif (!$request->vazio('cargoSigla') || !$request->vazio('cargoNome')) {
            $cargo = !$request->vazio('cargoSigla') ? $request->cargoSigla : $request->cargoNome;
            $nome .= ' | <b style="color: #ffd700;">' . $cargo . '</b>';
        }
        $telefone = '+55 (61) ';
        if (!$request->vazio('telefone') && !$request->vazio('celular')) {
            $telefone .= '<b>' . $request->celular . '</b>' . $request->telefone;
        } elseif (!$request->vazio('telefone') && !$request->vazio('celular')) {
            $telefoneTemp = !$request->vazio('telefone') ? $request->telefone : $request->celular;
            $telefone .= '<b>' . $telefoneTemp . '</b>';
        }

        return view('painel.assinatura.html', [
            'nome'     => $nome,
            'telefone' => $telefone,
            'email'    => !$request->vazio('email') ? $request->email . '@youhuul.com.br' : '',
        ]);
    }
}
