<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\UsuarioIndicacao\IndicacaoModel;
use App\Models\Site\Site\IndicacaoModel as SiteIndicacaoModel;
use App\Models\Site\Pesquisa\SalvarModel as SalvarPesquisaModel;

final class SiteController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function getPesquisa(): Response
    {
        return view('pesquisa.index');
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function postPesquisa(Request $request): Response
    {
        new SalvarPesquisaModel($request);
        return mensagemSucesso([], status: 201);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function indiqueAmigo(): Response
    {
        $texto = <<<HTML
            <p>O que você acha de liberar um acesso por 48h para um amigo?</p>
            <p>
                Isso mesmo, ele poderá acessar e usufruir de grande parte dos
                benefícios durante este período, conhecer melhor os benefícios de ser nosso associado.
            </p>
            <p>
                Basta colocar os dados do seu amigo, que a liberação do seu acesso será automática!
                Quando você indica, você também demonstra sua amizade!
            </p>
            <p>
                Lembre-se que para a liberação do acesso ocorrer, ele precisa ser da sua carreira
                e ainda não ser filiado à nossa entidade.
            </p>
        HTML;

        if (defined('CLUBE_ID') == '2dbd9e375eeabfbe859365dae0798f49') :
            $texto = <<<HTML
                <p>O que você acha de liberar um acesso por 30 dias para um amigo?</p>
                <p>
                    Isso mesmo, ele poderá acessar e usufruir de grande parte dos benefícios durante este período,
                    conhecer melhor os benefícios de ser nosso cliente.
                </p>
                <p>
                    Basta colocar os dados do seu amigo, que a liberação do seu acesso será automática!
                    Quando você indica, você também demonstra sua amizade!
                </p>
            HTML;
        elseif (defined('CLUBE_FINALIDADE') == 2) :
            $texto = <<<HTML
                <p>O que você acha de liberar um acesso por 48h para um amigo?</p>
                <p>
                    Isso mesmo, ele poderá acessar e usufruir de grande parte dos benefícios durante este período,
                    conhecer melhor os benefícios de ser nosso cliente.
                </p>
                <p>
                    Basta colocar os dados do seu amigo, que a liberação do seu acesso será automática!
                    Quando você indica, você também demonstra sua amizade!
                </p>'
            HTML;
        endif;

        return view('indicar_amigo.index', [
            'tituloPagina' => 'Indique para um amigo',
            'texto'        => $texto,
            'menu'         => 'indicar_amigo'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function postIndicarAmigo(Request $request): Response
    {
        $dado = (new SiteIndicacaoModel())->indicarAmigo($request);
        return mensagemSucesso($dado);
    }
    public function abrirModalEnquetePopup($id = null): Response
    {
        return view('popup.enquete');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalPopupImagem(): Response
    {
        return view('popup.imagem');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function regulamento_campanha(): Response
    {
        return view('regulamento.campanha');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getAjuda(): Response
    {
        return view('ajuda.index');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getIndiqueParceiro(): Response
    {
        return view('indicar_parceiro.index');
    }
}
