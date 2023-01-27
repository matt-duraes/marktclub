<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Analytics\OsModel;
use App\Models\Api\Analytics\AcessoDiaModel;
use App\Models\Api\Analytics\AnalyticsModel;
use App\Models\Api\Analytics\NavegadorModel;
use App\Models\Api\Analytics\DadoUsuarioModel;
use App\Models\Api\Analytics\DispositivoModel;
use App\Models\Api\Analytics\LojaMaisAcessadaModel;
use App\Models\Api\Analytics\UsuarioMaisAcessoModel;
use App\Models\Api\Analytics\PaginaMaisAcessadaModel;

final class RelatorioController extends Controller
{
    public function getDadoUsuario()
    {
        $Relatorio = new DadoUsuarioModel();
        $dado = $Relatorio->listarDados();

        return mensagemSucesso($dado);
    }

    public function getAcessoDia(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new AcessoDiaModel(
            new Data($request->de),
            new Data($request->ate)
        );
        $dado = $Relatorio->listarDado($request->de, $request->ate);

        return mensagemSucesso($dado);
    }

    public function getUsuarioMaisAcesso(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new UsuarioMaisAcessoModel(
            new Data($request->de),
            new Data($request->ate)
        );
        return mensagemSucesso(
            criptografarDado($Relatorio->listarDado(), lista: ['usuario'])
        );
    }
    public function getLojaMaisAcessada(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new LojaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate)
        );
        return mensagemSucesso($Relatorio->listarDado());
    }
    public function getPaginaMaisAcessada(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new PaginaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate)
        );
        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getDispositivo(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new DispositivoModel(
            new Data($request->de),
            new Data($request->ate)
        );

        return mensagemSucesso($Relatorio->listarDado());
    }
    public function getNavegador(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new NavegadorModel(
            new Data($request->de),
            new Data($request->ate)
        );

        return mensagemSucesso($Relatorio->listarDado());
    }
    public function getOs(Request $request)
    {
        $this->validarData($request);
        $Relatorio = new OsModel(
            new Data($request->de),
            new Data($request->ate)
        );

        return mensagemSucesso($Relatorio->listarDado());
    }

    public function getAnalytics(Request $request)
    {
        $Relatorio = new AnalyticsModel($request);
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
    public function postAnalyticsDownload()
    {
        $arquivo = DIRETORIO_PRIVADO . '/analytics/dump_' . TOKEN['app']->id . '.sql.zip';
        if (!file_exists($arquivo)) {
            mensagemStatus(404, localhost: 'O arquivo buscado não existe.');
        }
        return new Response(download: $arquivo);
    }

    private function validarData(Request $request)
    {
        $request->vazio('de', mensagem: 'A data de início da busca é obrigatória');
        $request->vazio('ate', mensagem: 'A data de final da busca é obrigatória');
    }
}
