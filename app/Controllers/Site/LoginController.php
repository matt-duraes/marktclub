<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\AuthHelper;
use Controller\Controller;
use App\Classes\TextoClube\Tipo;
use App\Models\Site\Login\LogarModel;
use App\Classes\ConstrutorClube\TipoAtivacao;
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
        return $this->loginRealizado();
    }

    private function loginRealizado(): Response
    {
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
        $TipoAtivacao = new TipoAtivacao();
        return view('login.buscar', [
            'tipoSiape'     => $TipoAtivacao::MATRICULA == TIPO_ATIVACAO,
            'tipoMatricula' => $TipoAtivacao::MATRICULA == TIPO_ATIVACAO
        ]);
    }

    public function postBuscarConta(Request $request): Response
    {
        $buscar = (new ApiHelper('usuario_cliente:ativar'))
            ->validar('Ocorreu um erro ao buscar seu usuário, por favor, tente novamente.')
            ->body([
                'chave'   => TIPO_ATIVACAO,
                'valor'   => $request->busca,
                'empresa' => EMPRESA_ID
            ])
            ->post('/usuario-cliente/ativar')
            ->object();

        return mensagemSucesso([
            'id'  => $buscar->dado->id,
            'cpf' => $buscar->dado->cpf
        ], status: 201);
    }

    public function ativar(Request $request): Response
    {
        if ($request->vazio('id') || $request->vazio('cpf')) {
            mensagemStatus(404);
        }
        return view('login.ativar', [
            'id'  => $request->id,
            'cpf' => $request->cpf
        ]);
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
    | SENHA
    |--------------------------------------------------------------------------
    */
    public function senha()
    {
        return view('login.senha');
    }

    public function postSenhaBuscar(Request $request)
    {
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $dado = $Api
            ->validar('Ocorreu um erro ao buscar seus dados, por favor, tente novamente.')
            ->json([
                'empresa' => EMPRESA_ID,
                'cpf'     => $Api->Crypt->encode($request->cpf),
            ])
            ->get('/usuario-cliente/senha')
            ->object();

        return mensagemSucesso([
            'id' => $dado->usuario
        ]);
    }

    public function postSenhaValidar(Request $request)
    {
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $dado = $Api
            ->validar('Ocorreu um erro ao validar seu código, por favor, tente novamente.')
            ->json([
                'usuario' => $request->usuario,
                'codigo'  => $request->codigo,
            ])
            ->post('/usuario-cliente/senha')
            ->object();

        return mensagemSucesso([
            'hash' => $dado->hash
        ]);
    }

    public function putSenhaAlterar(Request $request)
    {
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $Api
            ->validar('Ocorreu um erro ao atualizar sua senha, por favor, tente novamente.')
            ->json([
                'senha'   => $request->senha,
                'usuario' => $request->usuario,
                'hash'    => $request->hash,
            ])
            ->put('/usuario-cliente/senha');

        new LogarModel($request->cpf, $request->senha);
        return $this->loginRealizado();
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
