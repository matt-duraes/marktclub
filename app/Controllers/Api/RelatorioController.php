<?php

namespace App\Controllers\Api;

use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Models\Api\Analytics\AcessoDiaModel;
use App\Models\Api\Analytics\AnalyticsModel;
use App\Models\Api\Analytics\DadoUsuarioModel;
use App\Models\Api\Analytics\DispositivoModel;
use App\Models\Api\Analytics\LojaEquipe\DiaModel;
use App\Models\Api\Analytics\LojaMaisAcessadaModel;
use App\Models\Api\Analytics\LojaVendaModel;
use App\Models\Api\Analytics\NavegadorModel;
use App\Models\Api\Analytics\OsModel;
use App\Models\Api\Analytics\PaginaMaisAcessadaModel;
use App\Models\Api\Analytics\SalvarModel;
use App\Models\Api\Analytics\UsuarioMaisAcessoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;

final class RelatorioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ANALYTICS DO PAINEL
    |--------------------------------------------------------------------------
    */
    public function getLojaVenda(Request $request)
    {
        $Relatorio = new LojaVendaModel($request);
        $dado = $Relatorio->listarDados();

        return mensagemSucesso($dado);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getDadoUsuario(Request $request): Response
    {
        $RelatorioUsuario = new DadoUsuarioModel($request->empresa, $request->subempresa);
        return mensagemSucesso($RelatorioUsuario->listarDados());
    }

    public function getAcessoDia(Request $request)
    {
        $this->validarData($request);

        $Relatorio = new AcessoDiaModel(
            de: new Data($request->de),
            ate: new Data($request->ate),
            Empresa: $request->empresa
        );
        $dado = $Relatorio->listarDado($request->de, $request->ate);

        return mensagemSucesso($dado);
    }

    private function validarData(Request $request)
    {
        $request->vazio('de', mensagem: 'A data de início da busca é obrigatória');
        $request->vazio('ate', mensagem: 'A data de final da busca é obrigatória');
        if (!validarDate($request->de)) {
            mensagemErro('Campo inválido!', 'A data de começo da busca não é válida.');
        } elseif (!validarDate($request->ate)) {
            mensagemErro('Campo inválido!', 'A data de final da busca não é válida.');
        }
    }

    public function getUsuarioMaisAcesso(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new UsuarioMaisAcessoModel(
            new Data($request->de),
            new Data($request->ate),
            Empresa: $request->empresa
        );
        return mensagemSucesso(
            criptografarDado(
                dado: $Relatorio->listarDado(),
                criptografia: ['usuario'],
                lista: true
            )
        );
    }

    public function getLojaMaisAcessada(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new LojaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate),
            new TipoEstabelecimento($request->estabelecimento),
            Empresa: $request->empresa,
            parceiro: $request->parceiro
        );
        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getPaginaMaisAcessada(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new PaginaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate),
            Empresa: $request->empresa
        );
        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getDispositivo(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new DispositivoModel(
            new Data($request->de),
            new Data($request->ate),
            Empresa: $request->empresa
        );

        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getNavegador(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new NavegadorModel(
            new Data($request->de),
            new Data($request->ate),
            Empresa: $request->empresa
        );

        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getOs(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new OsModel(
            new Data($request->de),
            new Data($request->ate),
            Empresa: $request->empresa
        );

        return mensagemSucesso($Relatorio->listarDado());
    }

    /*
    |--------------------------------------------------------------------------
    | ANALYTICS EXTERNO
    |--------------------------------------------------------------------------
    */

    public function getLojaEquipeDia(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new DiaModel(
            new Data($request->de),
            new Data($request->ate),
            equipe: $request->equipe
        );

        return mensagemSucesso($Relatorio->retorno);
    }

    public function postAnalytics(Request $request)
    {
        new SalvarModel($request);
        return mensagemSucesso(['id' => uuid()], status: 201);
    }

    public function getAnalytics(Request $request)
    {
        $Relatorio = new AnalyticsModel($request);
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */

    public function postAnalyticsDownload()
    {
        $arquivo = DIRETORIO_PRIVADO . '/analytics/dump_' . TOKEN['app']->id . '.sql.zip';
        if (!file_exists($arquivo)) {
            mensagemStatus(404, localhost: 'O arquivo buscado não existe.');
        }
        return new Response(download: $arquivo);
    }
}
