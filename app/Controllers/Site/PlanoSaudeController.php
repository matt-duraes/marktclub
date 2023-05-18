<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\ConstrutorModel;
use App\Models\Site\Saude\OperadoraModel;

final class PlanoSaudeController extends Controller
{
    private $location = false;

    public function __construct()
    {
        parent::__construct();

        $construtor = (new ConstrutorModel())->montaPlanoDeSaude();
        //Para visualizar federal saúde só alterar essa define para federal
        define('CLUBE_ID', '80b010d457c4329f4aadacd5b57766c8');
        $this->defineLocation($construtor);
    }

    public function index()
    {
        $retorno = [
            'menu' => 'saude',
            'banner' => (new BannerModel())->saude(),
            'saudeBoleto' => (new OperadoraModel())->saudeBoleto(),
        ];

        if (CLUBE_ID != 'federal') {
            $retorno['lista'] = (new OperadoraModel())->listarDados();
        } else {
            $retorno['lista'] = (new OperadoraModel())->listarDadosFederal();
        }

        if ($this->location) {
            return location($this->location);
        }

        return view('plano_saude.index', $retorno);
    }

    private function defineLocation($construtor)
    {
        if (CLUBE_ID !== 'federal' && $this->location === false) {
            if ($construtor->vitoria && !$construtor->cnu && !$construtor->seguros) {
                $this->location = route('planosaude.unimedVitoria');
            } elseif ($construtor->cnu && !$construtor->vitoria && !$construtor->seguros) {
                $this->location = route('planosaude.centralnacional');
            } elseif ($construtor->seguros && !$construtor->vitoria && !$construtor->cnu) {
                $this->location = route('planosaude.unimedSeguro');
            }
        }
    }

    public function unimedVitoria()
    {
        return view(
            'plano_saude.unimedvitoria',
            [
                'menu' => 'saude',
                'lista'  => (new OperadoraModel())->listarDados()
            ]
        );
    }

    public function unimedflorianopolis()
    {
        return view('plano_saude.unimedflorianopolis', [
            'menu' => 'saude'
        ]);
    }

    public function tabela()
    {
        return view('plano_saude.geral.modal');
    }

    public function centralnacional()
    {
        return view('plano_saude.centralunimed', [
            'menu' => 'saude'
        ]);
    }

    public function amil()
    {
        return view('plano_saude.amil', [
            'menu' => 'saude'
        ]);
    }

    public function precoAmil(Request $request)
    {
        $views = [
            'rio_de_janeiro' => 'planosaude.geral.modalrio',
            'sao_paulo' => 'planosaude.geral.modalsp',
            'distrito_federal' => 'planosaude.geral.modaldf',
        ];

        if (isset($views[$request->local])) {
            return view($views[$request->local]);
        }

        return view('plano_saude.index', [
            'menu' => 'saude'
        ]);
    }

    public function federalSaude()
    {

        return view('plano_saude.federalSaude', [
            'menu' => 'federal_saude',
            'banner' => (new BannerModel())->saude(),
            // 'lista'  => (new PlanoModel())->listarDados()
        ]);
    }

    public function unimedSeguro()
    {
        return view('plano_saude.unimedSeguro', [
            'menu' => 'saude'
        ]);
    }

    public function simulacao($url = null)
    {
        return view('plano_saude.simulacao', [
            'menu' => 'saude',
            'operadora' => $url
        ]);
    }

    public function contratacao($simulacao = null)
    {

        return view('plano_saude.contratacao', [
            'menu' => 'saude',
            'simulacao' => $simulacao
        ]);
    }
}
