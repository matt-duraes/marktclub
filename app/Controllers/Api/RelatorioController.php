<?php

namespace App\Controllers\Api;

use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Models\Api\Analytics\AcessoDiaModel;
use App\Models\Api\Analytics\AnalyticsModel;
use App\Models\Api\Analytics\CampanhaVoucherModel;
use App\Models\Api\Analytics\DadoUsuarioModel;
use App\Models\Api\Analytics\DispositivoModel;
use App\Models\Api\Analytics\IndicacaoModel;
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

class RelatorioController extends Controller
{
    /**
     * Gera um relatório completo sobre os vendas
     *
     * @param Request $request Filtro por Data, Empresa, Subempresa e Parceiro
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getLojaVenda(Request $request): Response
    {
        $RelatorioLojaVenda = new LojaVendaModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa,
            $request->parceiro
        );
        return mensagemSucesso($RelatorioLojaVenda->gerarRelatorio());
    }

    /**
     * Gera um relatório completo sobre os usuários
     *
     * @param Request $request Filtro por Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getDadoUsuario(Request $request): Response
    {
        $RelatorioUsuario = new DadoUsuarioModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioUsuario->gerarRelatorio());
    }

    /**
     * Gera o relatório de acesso diário
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getAcessoDia(Request $request): Response
    {
        $RelatorioAcessoDia = new AcessoDiaModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioAcessoDia->gerarRelatorio());
    }

    /**
     * Gera o relatório de indicações
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getIndicacao(Request $request): Response
    {
        $RelatorioIndicacao = new IndicacaoModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioIndicacao->gerarRelatorio());
    }

    /**
     * Gera o relatório de campanha vouchers
     *
     * @param Request $request Filtro por Data e Empresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getCampanhaVoucher(Request $request): Response
    {
        $RelatorioCampanha = new CampanhaVoucherModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa
        );
        return mensagemSucesso($RelatorioCampanha->gerarRelatorio());
    }

    /**
     * Gera o relatório de acesso de usuários
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getUsuarioMaisAcesso(Request $request): Response
    {
        $RelatorioUsuarioAcesso = new UsuarioMaisAcessoModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        $relatorio = criptografarDado($RelatorioUsuarioAcesso->gerarRelatorio(), ['usuario'], lista: true);
        return mensagemSucesso($relatorio);
    }

    /**
     * Gera o relatório de lojas mais acessadas
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getLojaMaisAcessada(Request $request): Response
    {
        $RelatorioLojaMaisAcessada = new LojaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa,
            $request->parceiro,
            new TipoEstabelecimento($request->estabelecimento)
        );
        return mensagemSucesso($RelatorioLojaMaisAcessada->gerarRelatorio());
    }

    /**
     * Gera o relatório de páginas mais acessadas
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getPaginaMaisAcessada(Request $request): Response
    {
        $RelatorioMaisAcessada = new PaginaMaisAcessadaModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioMaisAcessada->gerarRelatorio());
    }

    /**
     * Gera um relatório completo sobre os dispositivos
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getDispositivo(Request $request): Response
    {
        $RelatorioDispositivo = new DispositivoModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioDispositivo->gerarRelatorio());
    }

    /**
     * Gera o relatório de acesso de navegadores
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getNavegador(Request $request): Response
    {
        $RelatorioNavegador = new NavegadorModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioNavegador->gerarRelatorio());
    }

    /**
     * Gera o relatório de acesso de sistemas operacionais
     *
     * @param Request $request Filtro por Data, Empresa e Subempresa
     *
     * @return Response Relatório formatado para visualização
     * @throws Excecao
     */
    public function getOs(Request $request): Response
    {
        $RelatorioSistemaOperacional = new OsModel(
            new Data($request->de),
            new Data($request->ate),
            $request->empresa,
            $request->subempresa
        );
        return mensagemSucesso($RelatorioSistemaOperacional->gerarRelatorio());
    }

    // TODO: Refatorar essa função
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

    /**
     * Função responsável por salvar o analytics completo
     *
     * @param Request $request Dados coletados
     *
     * @return Response
     * @throws Excecao
     */
    public function postAnalytics(Request $request): Response
    {
        new SalvarModel($request);
        return mensagemSucesso(['id' => uuid()], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getAnalytics(Request $request): Response
    {
        $Relatorio = new AnalyticsModel($request);
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function postAnalyticsDownload(): Response
    {
        $arquivo = DIRETORIO_PRIVADO . '/analytics/dump_' . TOKEN['app']->id . '.sql.zip';
        if (!file_exists($arquivo)) {
            mensagemStatus(404, localhost: 'O arquivo buscado não existe.');
        }
        return new Response(download: $arquivo);
    }
}
