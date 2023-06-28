<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Erro\Excecao;
use Http\Response;
use Http\Request;
use App\Models\Site\SosMulher\ListarModel;
use App\Models\Site\Pesquisa\SalvarModel as SalvarPesquisaModel;

final class SiteController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function getPesquisa(): Response
    {
        $listaConhece = [
            (object)['name' => '0800', 'label' => '0800', 'valor' => '0800'],
            (object)['name' => 'cinema', 'label' => 'Cinema', 'valor' => 'CINEMA'],
            (object)['name' => 'lojaProxima', 'label' => 'Lojas Próximas', 'valor' => 'Lojas Próximas'],
            (object)['name' => 'indicacaoLoja', 'label' => 'Indicação de Lojas', 'valor' => 'INDICACAO'],
            (object)['name' => 'turismo', 'label' => 'Turismo', 'valor' => 'TURISMO'],
            (object)['name' => 'saude', 'label' => 'Saúde', 'valor' => 'SAUDE'],
            (object)['name' => 'credito', 'label' => 'Crédito', 'valor' => 'CREDITO ALFA'],
            (object)['name' => 'odontologico', 'label' => 'Plano Odontológico', 'valor' => 'ODONTOLOGICO'],
            (object)['name' => 'silium', 'label' => 'Cashback Silium', 'valor' => 'SILIUM'],
            (object)['name' => 'lojas', 'label' => 'Lojas', 'valor' => 'CONVENIOS'],
            (object)['name' => 'dependente', 'label' => 'Adicionar Dependentes', 'valor' => 'DEPENDENTES'],
            (object)['name' => 'preferencia', 'label' => 'Preferências', 'valor' => 'PREFERENCIAS'],
            (object)['name' => 'whatsapp', 'label' => 'Whatsapp', 'valor' => 'WHATSAPP'],
            (object)['name' => 'promocao', 'label' => 'Promoção', 'valor' => 'PROMOCOES'],
            (object)['name' => 'medicamento', 'label' => 'Medicamento', 'valor' => 'MEDICAMENTO'],
            (object)['name' => 'nenhum', 'label' => 'Nenhum', 'valor' => 'NENHUM']
        ];

        return view('pesquisa.index', [
            'sistemaConhece' => $listaConhece
        ]);

    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function postPesquisa(Request $request): Response
    {
        $pesquisa = new SalvarPesquisaModel($request);
        $pesquisa = $pesquisa->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function sosmulher(): Response
    {
        return view('sosmulher.index', [
            'parceiro' => [1, 2, 3],
            'parceiroTipo' => 'sosmulher',
            'dado'        => (new ListarModel())->listarDados(),
            'lista'        => (new ListarModel())->listarRelacionado(),
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function indiqueAmigo(): Response
    {
        define('CLUBE_FINALIDADE', 1);

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
            'texto'        => $texto
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalEnquetePopup($id = null): Response
    {
        return view('popup.enquete');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalPopupImagem($id = null): Response
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

}
