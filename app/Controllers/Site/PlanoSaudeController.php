<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Controller\Controller;
use Modules\EnderecoEstado;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\BannerModel;
use App\Models\Site\Saude\endereco\CidadeModel;
use App\Models\Site\Saude\endereco\EstadoModel;
use App\Models\Site\Saude\simulacao\BuscarModel;

final class PlanoSaudeController extends Controller
{
    public function escolherEstado(): Response
    {
        return view('plano_saude.escolherEstado', [
            'estado' => (new EstadoModel())->retorno,
        ]);
    }

    public function postEscolherCidade(Request $request): Response
    {
        return mensagemSucesso([
            'cidade' => (new CidadeModel(new EnderecoEstado($request->estado)))->retorno,
        ], status: 201);
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
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSimulacao(Request $request): Response
    {
        $Simular = new BuscarModel(
            plano: $request->plano,
            titular: new Data($request->titular),
            dependente: jsonDecode($request->dependente, true, true)
        );
        return mensagemSucesso($Simular->retorno);
    }

    /**
     * @param $url
     *
     * @return Response
     * @throws Excecao
     */
    public function simulacao($uri = null): Response
    {
        return view('plano_saude.simulacao', [
            'menu'  => 'saude',
            'plano' => $uri
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
            'simulacao' => $simulacao,
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
                'endereco_estado'             => $request->endereco_estado,
            ])
            ->post('/saude-contratacao')
            ->object();

        return mensagemSucesso($dado, 201);
    }
}
