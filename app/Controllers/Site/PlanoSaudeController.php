<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\BannerModel;
use App\Models\Site\Saude\OperadoraModel;
use App\Models\Site\Saude\SimulacaoViewModel;

final class PlanoSaudeController extends Controller
{
    public function index()
    {
        return view('plano_saude.index', [
            'menu'   => 'saude',
            'lista'  => (new OperadoraModel())->listarDados()
        ]);
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

    public function postRealizarSimulacao(Request $request, $url = null)
    {
        $dado = ((new ClubeApiHelper()))
        ->body([
            'operadora'        => $request->operadora,
            'regiao'           => $request->regiao,
            'plano'            => $request->plano,
            'titular'          => $request->titular,
            'dependentes'      => $request->dependentes,
            'acomodacao'       => $request->acomodacao
        ])
        ->post('/saude/simulacao')
        ->object();

        return new Response(json:$dado);
    }

    public function simulacao($url = null)
    {
        $operadora = ($url == 'unimed-vitoria') ? 'unimed' : $url;
        if ($operadora == 'central-nacional-unimed-florianopolis') {
            $operadora = str_replace('-', '_', $url);
        }
        if ($operadora == 'unimed-seguros') {
            $operadora = str_replace('-', '_', $url);
        }
        return view('plano_saude.simulacao', [
            'menu'      => 'saude',
            'operadora' => $operadora,
            'Simulacao' => new SimulacaoViewModel($operadora)
        ]);
    }

    public function contratacao(string $simulacao)
    {
        return view('plano_saude.contratacao', [
            'menu'      => 'saude',
            'simulacao' => $simulacao
        ]);
    }

    public function postRealizarContratacao(Request $request)
    {
        $dado = (new ClubeApiHelper())
            ->validar('Erro ao salvar contratação, por favor, tente novamente.', login: true)
            ->body([
                'id_simulacao'                => $request->id_simulacao,
                'nome'                        => $request->nome,
                'naturalidade'                => $request->naturalidade,
                'documento_cpf'               => $request->cpf,
                'data_nascimento'             => $request->data_nascimento,
                'genero'                      => $request->genero,
                'estado_civil'                => $request->estado_civil,
                'peso'                        => $request->peso,
                'altura'                      => $request->altura,
                'documento_rg'                => $request->rg,
                'orgao_expedidor'             => $request->orgao_expedidor,
                'nome_mae'                    => $request->nome_mae,
                'responsavel_nome'            => $request->responsavel_nome,
                'responsavel_cpf'             => $request->responsavel_cpf,
                'responsavel_rg'              => $request->responsavel_rg,
                'responsavel_orgao_expedidor' => $request->responsavel_orgao_expedidor,
                'email'                       => $request->email_pessoal,
                'telefone_celular'            => $request->telefone_celular,
                'telefone_residencial'        => $request->telefone_residencial,
                'telefone_comercial'          => $request->telefone_comercial,
                'telefone_comercial_ramal'    => $request->telefone_comercial_ramal,
                'endereco_cep'                => $request->endereco_cep,
                'endereco_bairro'             => $request->endereco_bairro,
                'endereco_logradouro'         => $request->endereco_logradouro,
                'endereco_numero'             => $request->endereco_numero,
                'endereco_complemento'        => $request->endereco_complemento,
                'endereco_cidade'             => $request->endereco_cidade,
                'endereco_estado'             => $request->endereco_estado
            ])
            ->post('/saude/contratacao')
            ->object();

        return mensagemSucesso($dado, status: 201);
    }
}
