<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\AuthHelper;
use Controller\Controller;
use App\Classes\TextoClube\Tipo;
use App\Models\Site\Login\LogarModel;
use App\Models\Site\Contato\SalvarModel as SalvarContatoModel;

final class LoginController extends Controller
{
    public function index(): Response
    {
        return view('login.index');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login()
    {
        return view('login.login', [
            'api'        => API,
            'link_login' => LINK_LOGIN
        ]);
    }

    public function postLogin(Request $request): Response
    {
        new LogarModel($request->login, $request->senha);
        $link = (new AuthHelper())->location();
        return mensagemSucesso([
            'link' => str_contains($link, '/login') ? LINK : $link
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | ATIVAR
    |--------------------------------------------------------------------------
    */
    public function buscarConta()
    {
        return view('login.buscar');
    }

    public function postBuscarConta(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function ativar(): Response
    {
        return view('login.ativar');
    }

    public function postAtivar(Request $request): Response
    {
        return new Response(json: [], status: 201);
    }

    public function faq(): Response
    {
        try {
            $lista = (new ApiHelper(scope: 'texto_clube:listar'))
            ->json([
                'empresa' => EMPRESA_ID,
                'pagina'  => 1,
                'tipo'    => Tipo::FAQ,
                'status'  => 'ativo'
            ])
            ->get('/texto-clube')
            ->object()->dado->lista;
        } catch (\Throwable) {
            $lista = [];
        }

        return view('login.faq', ['faq' => $lista]);
    }

    /*
    |--------------------------------------------------------------------------
    | APP
    |--------------------------------------------------------------------------
    */
    public function app()
    {
        if (!MENU_BAIXAR_APP) {
            mensagemStatus(404);
        } elseif (DISPOSITIVO_IOS && !empty(LINK_APP_IOS)) {
            return new Response(url: LINK_APP_IOS);
        } elseif (DISPOSITIVO_ANDROID && !empty(LINK_APP_ANDROID)) {
            return new Response(url: LINK_APP_ANDROID);
        }
        return view('login.app');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTATO
    |--------------------------------------------------------------------------
    */
    public function contato()
    {
        return view('login.contato');
    }

    public function postContato(Request $request): Response
    {
        $contato = new SalvarContatoModel($request);
        $contato = $contato->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }
}
