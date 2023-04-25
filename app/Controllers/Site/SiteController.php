<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Erro\Excecao;
use Http\Response;

final class SiteController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function pesquisa(): Response
    {
        $listaConhece = [
            (object)['name' => '0800', 'label' => '0800', 'valor' => '1'],
            (object)['name' => 'cinema', 'label' => 'Cinema', 'valor' => '2'],
            (object)['name' => 'lojaProxima', 'label' => 'Lojas Próximas', 'valor' => '3'],
            (object)['name' => 'indicacaoLoja', 'label' => 'Indicação de Lojas', 'valor' => '4'],
            (object)['name' => 'turismo', 'label' => 'Turismo', 'valor' => '5'],
            (object)['name' => 'saude', 'label' => 'Saúde', 'valor' => '6'],
            (object)['name' => 'credito', 'label' => 'Crédito', 'valor' => '7'],
            (object)['name' => 'odontologico', 'label' => 'Plano Odontológico', 'valor' => '8'],
            (object)['name' => 'silium', 'label' => 'Cashback Silium', 'valor' => '9'],
            (object)['name' => 'lojas', 'label' => 'Lojas', 'valor' => '10'],
            (object)['name' => 'dependente', 'label' => 'Adicionar Dependentes', 'valor' => '11'],
            (object)['name' => 'preferencia', 'label' => 'Preferências', 'valor' => '12'],
            (object)['name' => 'whatsapp', 'label' => 'Whatsapp', 'valor' => '13'],
            (object)['name' => 'promocao', 'label' => 'Promoção', 'valor' => '14'],
            (object)['name' => 'medicamento', 'label' => 'Medicamento', 'valor' => '15']
        ];

        return view('pesquisa.index', [
            'sistemaConhece' => $listaConhece
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function sosmulher(): Response
    {
        return view('sosmulher.index', [
            'parceiro' => [1, 2, 3]
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function indiqueAmigo(): Response
    {
        define('CLUBE_ID', '80b010d457c4329f4aadacd5b57766c8');
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

        if (CLUBE_ID == '2dbd9e375eeabfbe859365dae0798f49') :
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
        elseif (CLUBE_FINALIDADE == 2) :
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

        return view('indicacao.index', [
            'tituloPagina' => 'Indique para um amigo',
            'texto'        => $texto
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalEnquetePopup(): Response
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
}
