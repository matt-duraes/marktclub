<?php

namespace App\Controllers\Site;

use App\Helpers\ClubeApiHelper;
use Http\Request;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Saude\OperadoraModel;
use Http\Response;

final class PlanoSaudeController extends Controller
{
    private $location = false;

    public function index()
    {
        return view('plano_saude.index', [
            'lista' => (new OperadoraModel())->listarDados()
        ]);
    }

    private function defineLocation($construtor)
    {
        if (defined('CLUBE_ID') !== 'federal' && $this->location === false) {
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
                'menu'   => 'saude',
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
            'rio_de_janeiro'   => 'planosaude.geral.modalrio',
            'sao_paulo'        => 'planosaude.geral.modalsp',
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
            'menu'   => 'federal_saude',
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

    public function getRealizarSimulacao(Request $request)
    {
        $dado = ((new ClubeApiHelper()))
        ->body([
            'operadora'        => $request->operadora,
            'regiao'           => $request->regiaoSelecionada,
            'plano'            => $request->planoSelecionado,
            'data_nascimento'  => $request->dtNascimentoTitular,
            'dependentes'      => $request->dtNascimentoDependentes,
            'acomodacao'       => $request->acomodacao
        ])
        ->post('/saude/simulacao')
        ->object();

        return new Response(json:$dado);
    }

    public function simulacao($url = null)
    {
        return view('plano_saude.simulacao', [
            'menu'      => 'saude',
            'operadora' => $url
        ]);
    }

    public function contratacao($simulacao = null)
    {
        return view('plano_saude.contratacao', [
            'menu'      => 'saude',
            'simulacao' => $simulacao
        ]);
    }

    public function postRealizarContratacao(Request $request, string $idSimulacao)
    {
        $teste = ((new ClubeApiHelper()))
        ->body([
            'id_simulacao'                         => $request->id_simulacao,
            'nome'                                 => $request->nome,
            'naturalidade'                         => $request->naturalidade,
            'documento_cpf'                        => $request->cpf,
            'data_nascimento'                      => $request->data_nascimento,
            'sexo'                                 => $request->genero,
            'estado_civil'                         => $request->estado_civil,
            'peso'                                 => $request->peso,
            'altura'                               => $request->altura,
            'documento_rg'                         => $request->rg,
            'orgao_expedidor'                      => $request->orgao_expedidor,
            'filiacao'                             => $request->responsavel,
            'nome_responsavel'                     => $request->responsavel_nome,
            'cpf_responsavel'                      => $request->responsavel_cpf,
            'rg_responsavel'                       => $request->responsavel_rg,
            'email'                                => $request->email_pessoal,
            'telefone_celular'                     => $request->telefone_celular,
            'telefone_residencial'                 => $request->telefone_residencial,
            'telefone_comercial'                   => $request->telefone_comercial,
            'ramal'                                => $request->ramal,
            'cep'                                  => $request->cep,
            'bairro'                               => $request->bairro,
            'endereco'                             => $request->logradouro,
            'numero'                               => $request->numero,
            'complemento'                          => $request->complemento,
            'cidade'                               => $request->cidade,
            'estado'                               => $request->estado,
        ])
        ->post('/saude/contratacao')
        ->object();
    }
}
