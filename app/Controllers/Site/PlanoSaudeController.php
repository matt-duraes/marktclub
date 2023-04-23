<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Models\Site\Saude\SimulacaoModel;

final class PlanoSaudeController extends Controller
{
    public function index()
    {
        return view('planosaude.index', [
            'menu' => 'saude'
        ]);
    }
    public function detalhe()
    {
        return view('planosaude.detalhe', [
            'menu' => 'saude'
        ]);
    }
    public function unimedvitoria()
    {
        return view('planosaude.unimedvitoria', [
            'menu' => 'saude'
        ]);
    }
    public function unimedflorianopolis()
    {
        return view('planosaude.unimedflorianopolis', [
            'menu' => 'saude'
        ]);
    }
    public function tabela()
    {
        return view('planosaude.geral.modal');
    }
    public function centralnacional()
    {
        return view('planosaude.centralunimed', [
            'menu' => 'saude'
        ]);
    }
    public function amil()
    {
        return view('planosaude.amil', [
            'menu' => 'saude'
        ]);
    }
    public function precoAmil(Request $request)
    {
        if ($request->local == 'rio_de_janeiro') {
            return view('planosaude.geral.modalrio');
        } elseif ($request->local == 'sao_paulo') {
            return view('planosaude.geral.modalsp');
        } elseif ($request->local == 'distrito_federal') {
            return view('planosaude.geral.modaldf');
        }

        return view('planosaude.index', [
            'menu' => 'saude'
        ]);
    }
    public function federalSaude()
    {
        return view('planosaude.federalSaude', [
            'menu' => 'federal_saude'
        ]);
    }
    public function unimedSeguro()
    {
        return view('planosaude.unimedSeguro', [
            'menu' => 'saude'
        ]);
    }


    public function simulacao($url = null)
    {

        if ($url == 'unimed-vitoria') {
            $tituloPagina = 'Unimed Vitória';
            $operadora = 'unimed-vitoria';
        } elseif ($url == 'unimed-florianopolis') {
            $tituloPagina = 'Unimed Florianópolis';
            $operadora = 'unimed-florianopolis';
        } elseif ($url == 'amil') {
            $tituloPagina = 'Amil';
            $operadora = 'amil';
        } elseif ($url == 'unimed-seguros') {
            $tituloPagina = 'Unimed Seguros';
            $operadora = 'unimed-seguro';
        }

        return view('planosaude.simulacao', [
            'menu' => 'saude',
            'tituloPagina' => $tituloPagina,
            'operadora' => $operadora
        ]);
    }

    public function contratacao($simulacao = null)
    {

        return view('planosaude.contratacao', [
            'menu' => 'saude',
            'tituloPagina' => 'Contrate o Plano de Saúde ',
            'simulacao' => $simulacao
        ]);
    }
}
