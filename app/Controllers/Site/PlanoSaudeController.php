<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\BannerModel;
use App\Models\Site\Saude\OperadoraModel;
use App\Models\Site\Saude\SimulacaoViewModel;
use App\Models\Site\Saude\FazerSimulacaoModel;

final class PlanoSaudeController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $lista = (new OperadoraModel())->listarDados();
        if (empty($lista->lista)) {
            mensagemStatus(404);
        } elseif (count($lista->lista) == 1) {
            return new Response(url: $lista->lista[0]->link);
        }

        return view('plano_saude.index', [
            'menu'  => 'saude',
            'lista' => $lista
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function unimedVitoria(): Response
    {
        return view('plano_saude.unimedvitoria', [
            'menu'  => 'saude',
            'lista' => (new OperadoraModel())->listarDados()
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function unimedflorianopolis(): Response
    {
        return view('plano_saude.unimedflorianopolis', [
            'menu' => 'saude'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function tabela(): Response
    {
        return view('plano_saude.geral.modal');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function centralnacional(): Response
    {
        return view('plano_saude.centralunimed', [
            'menu' => 'saude'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function amil(): Response
    {
        return view('plano_saude.amil', [
            'menu' => 'saude'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function precoAmil(Request $request): Response
    {
        $views = [
            'rio_de_janeiro'   => 'plano_saude.geral.modalrio',
            'sao_paulo'        => 'plano_saude.geral.modalsp',
            'distrito_federal' => 'plano_saude.geral.modaldf',
        ];

        if (isset($views[$request->local])) {
            return view($views[$request->local]);
        }

        return view('plano_saude.index', [
            'menu' => 'saude'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function federalSaude(): Response
    {
        return view('plano_saude.federalSaude', [
            'menu'   => 'federal_saude',
            'banner' => (new BannerModel())->saude(),
            // 'lista'  => (new PlanoModel())->listarDados()
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function unimedSeguro(): Response
    {
        return view('plano_saude.unimedSeguro', [
            'menu' => 'saude'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postRealizarSimulacao(Request $request): Response
    {
        $Simulacao = new FazerSimulacaoModel($request);
        return mensagemSucesso($Simulacao->simulacao, 201);
    }

    /**
     * @param $url
     *
     * @return Response
     * @throws Excecao
     */
    public function simulacao($url = null): Response
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

    /**
     * @param string $simulacao
     *
     * @return Response
     * @throws Excecao
     */
    public function contratacao(string $simulacao): Response
    {
        return view('plano_saude.contratacao', [
            'menu'      => 'saude',
            'simulacao' => $simulacao
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postRealizarContratacao(Request $request): Response
    {
        $dado = (new ClubeApiHelper())
            ->validar('Erro ao salvar contratação, por favor, tente novamente.', login: true)
            ->body([
                'id_saude_simulacao'          => $request->id_saude_simulacao,
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
                'email_pessoal'               => $request->email_pessoal,
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
            ->post('/saude-contratacao')
            ->object();

        return mensagemSucesso($dado, 201);
    }
}
