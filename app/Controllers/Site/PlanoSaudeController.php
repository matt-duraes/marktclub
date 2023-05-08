<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\ConstrutorModel;
use App\Models\Site\Saude\OperadoraModel;

final class PlanoSaudeController extends Controller
{
    private $location = false;
    private $cnu;
    private $vitoria;
    private $seguros;
    private $amil;

    public function __construct()
    {
        parent::__construct();

        $construtor = (new ConstrutorModel())->buscar();
        $_SESSION['CLUBE'] = $construtor;
        //Para visualizar federal saúde só alterar essa define para federal
        define('CLUBE_ID', '80b010d457c4329f4aadacd5b57766c8');

        $this->cnu = isset($_SESSION['CLUBE']->saude->cnu) && $_SESSION['CLUBE']->saude->cnu == 1;
        $this->seguros = isset($_SESSION['CLUBE']->saude->seguros) && $_SESSION['CLUBE']->saude->seguros == 1;
        $this->vitoria = isset($_SESSION['CLUBE']->saude->vitoria) && $_SESSION['CLUBE']->saude->vitoria == 1;
        $this->amil = isset($_SESSION['CLUBE']->saude->amil) && $_SESSION['CLUBE']->saude->amil == 1;
        $this->unimedflorianopolis = isset($_SESSION['CLUBE']->saude->unimedflorianopolis) && $_SESSION['CLUBE']->saude->unimedflorianopolis == 1;

        if (CLUBE_ID != 'federal') {
            if ($this->vitoria && !$this->cnu && !$this->seguros) {
                $this->location = route('planosaude.unimed');
            } elseif ($this->cnu && !$this->vitoria && !$this->seguros) {
                $this->location = route('planosaude.centralnacional');
            } elseif ($this->seguros && !$this->vitoria && !$this->cnu) {
                $this->location = route('planosaude.unimedSeguro');
            }
        }
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

        return view('planosaude.index', $retorno);
    }

    public function detalhe()
    {
        return view('planosaude.detalhe', [
            'menu' => 'saude',
            'lista'  => (new OperadoraModel())->listarDados()
        ]);
    }

    public function unimedvitoria()
    {
        return view('planosaude.unimedvitoria', [
            'menu' => 'saude',
            'lista'  => (new OperadoraModel())->listarDados()

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
        $views = [
            'rio_de_janeiro' => 'planosaude.geral.modalrio',
            'sao_paulo' => 'planosaude.geral.modalsp',
            'distrito_federal' => 'planosaude.geral.modaldf',
        ];

        if (isset($views[$request->local])) {
            return view($views[$request->local]);
        }

        return view('planosaude.index', [
            'menu' => 'saude'
        ]);

    }

    public function federalSaude()
    {

        return view('planosaude.federalSaude', [
            'menu' => 'federal_saude',
            'banner' => (new BannerModel())->saude(),
            'lista'  => (new PlanoModel())->listarDados()
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
        return view('planosaude.simulacao', [
            'menu' => 'saude',
            'operadora' => $url
        ]);
    }

    public function contratacao($simulacao = null)
    {

        return view('planosaude.contratacao', [
            'menu' => 'saude',
            'simulacao' => $simulacao
        ]);
    }
}
