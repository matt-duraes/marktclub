<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Analytics\AnalyticsModel;
use App\Models\Api\Analytics\DispositivoModel;
use App\Models\Api\Analytics\MaisAcessadoModel;
use App\Models\Api\Analytics\UsuarioAcessoModel;
use App\Models\Api\UsuarioCliente\Relatorio\GeneroModel;
use App\Models\Api\UsuarioCliente\Relatorio\SemDadoModel;
use App\Models\Api\UsuarioCliente\Relatorio\SituacaoModel;
use App\Models\Api\UsuarioCliente\Relatorio\EstadoCivilModel;
use App\Models\Api\UsuarioCliente\Relatorio\FaixaEtariaModel;
use App\Models\Api\UsuarioCliente\Relatorio\AtualizarDadoModel;
use App\Models\Api\UsuarioCliente\Relatorio\EstadoModel as UsuarioEstado;
use App\Models\Api\UsuarioCliente\Relatorio\StatusModel as UsuarioStatus;

final class RelatorioController extends Controller
{
    public function getUsuarioStatus()
    {
        $Relatorio = new UsuarioStatus();
        $dado = $Relatorio->status();

        return mensagemSucesso($dado);
    }
    public function getUsuarioEstado()
    {
        $Relatorio = new UsuarioEstado();
        $dado = $Relatorio->estado();

        return mensagemSucesso($dado);
    }

    public function getUsuarioAcesso(Request $request)
    {
        $Relatorio = new UsuarioAcessoModel();
        $dado = $Relatorio->acesso($request->de, $request->ate);

        return mensagemSucesso($dado);
    }

    public function getMaisAcessado(Request $request)
    {
        $Relatorio = new MaisAcessadoModel();
        if ($request->local == 'pagina') {
            $dado = $Relatorio->paginaMaisAcessada($request->de, $request->ate);
        } else if ($request->local == 'parceiro') {
            $dado = $Relatorio->parceiroMaisAcessada($request->de, $request->ate);
        } else if ($request->local == 'usuario') {
            $dado = $Relatorio->usuarioComMaisAcesso($request->de, $request->ate);
        } else {
            mensagemStatus(404);
        }

        return mensagemSucesso($dado);
    }

    public function getDispositivo(Request $request)
    {
        $Relatorio = new DispositivoModel();
        if ($request->tipo == 'dispositivo') {
            $dado = $Relatorio->porDispositivo($request->de, $request->ate);
        } else if ($request->tipo == 'navegador') {
            $dado = $Relatorio->porNavegador($request->de, $request->ate);
        } else if ($request->tipo == 'os') {
            $dado = $Relatorio->porOS($request->de, $request->ate);
        } else {
            mensagemStatus(404);
        }

        return mensagemSucesso($dado);
    }

    public function getUsuarioGenero()
    {
        $Relatorio = new GeneroModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }

    public function getUsuarioSituacao()
    {
        $Relatorio = new SituacaoModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
    public function getUsuarioEstadoCivil()
    {
        $Relatorio = new EstadoCivilModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
    public function getUsuarioFaixaEtaria()
    {
        $Relatorio = new FaixaEtariaModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
    public function getUsuarioSemDado()
    {
        $Relatorio = new SemDadoModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
    public function getUsuarioAtualizarDado()
    {
        $Relatorio = new AtualizarDadoModel();
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
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
}
