<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Controller\Controller;
use App\Classes\TextoClube\Tipo;
use App\Models\Site\Login\LogarModel;
use App\Models\Site\Ativar\SalvarModel;
use App\Models\Site\Login\LoginApiModel;
use App\Models\Site\Login\ComunicacaoModel;
use App\Classes\ConstrutorClube\TipoAtivacao;
use App\Models\Site\Contato\SalvarModel as SalvarContatoModel;

final class LoginController extends Controller
{
    public function index(Request $request): Response
    {
        if (!TELA_LOGIN) {
            return new Response(url: LINK_LOGIN);
        }

        $dado = (new ComunicacaoModel())->buscarBanners() ?? '';
        return view('login.index', [
            'banner'             => $dado->lista,
            'quantidade_banners' => $dado->quantidade_banners,
            'location'           => base64Decode($request->chave('location', ''), true)
        ]);
    }

    public function youhuul()
    {
        return view('login.youhuul');
    }

    /*
    |--------------------------------------------------------------------------
    | DIGIO
    |--------------------------------------------------------------------------
    */
    public function getDigioApi(Request $request)
    {
        $clube = 'digio';
        $link = (new ApiHelper(scope: 'login:' . $clube))
            ->body([
                'usuario' => $request->chave('client-id', ''),
                'clube'   => $clube
            ])
            ->post('/login/digio')
            ->object()->dado->link ?? LINK;

        return new Response(url: $link);
    }

    public function digio()
    {
        return $this->loginBasico(
            titulo: 'Bem vindo ao Descontinho',
            texto: 'Para acessar seu clube, você deve ser correntista. Baixe o APP para seu celular',
            android: 'https://play.google.com/store/apps/details?id=br.com.digio&hl=pt_BR&gl=US',
            ios: 'https://apps.apple.com/br/app/digio-seu-cart%C3%A3o-de-cr%C3%A9dito/id1128793569',
        );
    }

    public function uber()
    {
        return $this->loginBasico(
            titulo: 'Bem vindo ao Uber Conta by Digio',
            texto: 'Para acessar seu clube, você deve ser correntista. Baixe o APP para seu celular',
            android: 'https://play.google.com/store/apps/details?id=br.com.digio.uber&hl=pt_BR&gl=US',
            ios: 'https://apps.apple.com/br/app/uber-conta/id1550784531',
        );
    }

    private function loginBasico(
        string $titulo = '',
        string $texto = '',
        string $android = '',
        string $ios = ''
    ) {
        return view('login.basico', [
            'titulo'  => $titulo,
            'texto'   => $texto,
            'android' => $android,
            'ios'     => $ios,
        ]);
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

    public function api(string $hash)
    {
        try {
            new LoginApiModel($hash);
        } catch (\Throwable) {
            return new Response(url: LINK);
        }
        return new Response(url: LINK);
    }

    private function loginRealizado(): Response
    {
        return mensagemSucesso(['id' => uuid()], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | ATIVAR
    |--------------------------------------------------------------------------
    */
    public function ativarBuscar()
    {
        $TipoAtivacao = new TipoAtivacao();
        return view('login.ativar.buscar', [
            'tipoSiape'      => $TipoAtivacao::SIAPE == TIPO_ATIVACAO,
            'tipoMatricula'  => $TipoAtivacao::MATRICULA == TIPO_ATIVACAO,
        ]);
    }

    public function postAtivarBuscar(Request $request): Response
    {
        $valor = $request->busca;
        if (TIPO_ATIVACAO == 'cpf') {
            $valor = str_replace(['.', '-'], '', $valor);
        }
        if ($request->tipo_usuario == 'indicado') {
            $buscar = (new ApiHelper('usuario_indicacao:ativar'))
                ->validar('Ocorreu um erro ao validar seu código, por favor, tente novamente.')
                ->body([
                    'email'   => $this->crypt()->encode($request->busca),
                ])
                ->post('/usuario-indicacao/ativar')
                ->object();
        } else {
            $buscar = (new ApiHelper('usuario_cliente:ativar'))
                ->body([
                    'tipo_usuario' => $request->tipo_usuario,
                    'chave'        => TIPO_ATIVACAO,
                    'valor'        => $valor,
                    'empresa'      => CLUBE_EMPRESA
                ])
                ->post('/usuario-cliente/ativar')
                ->object();
        }

        if ($buscar->status == 'erro') {
            return mensagemErro(404, $buscar->erro->mensagem);
        }

        return mensagemSucesso([
            'hash'  => $buscar->dado->hash ?? '',
            'cpf'   => $buscar->dado->cpf ?? ''
        ], status: 201);
    }

    public function ativarSalvar(Request $request): Response
    {
        if ($request->vazio('hash')) {
            mensagemStatus(404);
        }

        return view('login.ativar.salvar', [
            'hash'           => $request->hash,
            'cpf'            => $request->cpf,
            'tipo_usuario'   => $request->tipo_usuario
        ]);
    }

    public function postAtivarValidar(Request $request): Response
    {
        $dado = (new ApiHelper('usuario_indicacao:ativar'))
            ->validar('Ocorreu um erro ao validar seu código, por favor, tente novamente.')
            ->body([
                'hash'   => $request->hash,
            ])
            ->post('/usuario-indicacao/ativar')
            ->object();

        if ($dado->status == 'erro' && $dado->erro->titulo == 'Indicação já ativada') {
            return mensagemErro($dado->erro->titulo, $dado->erro->mensagem);
        }

        return mensagemSucesso([
            'hash' => $dado->dado->hash
        ]);
    }

    public function postAtivarSalvar(Request $request): Response
    {
        new SalvarModel($request, $this->crypt());
        new LogarModel($request->cpf, $request->senha);
        return $this->loginRealizado();
    }

    public function faq(): Response
    {
        try {
            $lista = (new ApiHelper(scope: 'texto_clube:listar'))
            ->json([
                'empresa' => CLUBE_EMPRESA,
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
        $Crypt = $this->crypt();
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $dado = $Api
            ->validar('Ocorreu um erro ao buscar seus dados, por favor, tente novamente.')
            ->json([
                'empresa' => CLUBE_EMPRESA,
                'cpf'     => $Crypt->encode($request->cpf),
            ])
            ->get('/usuario-cliente/senha')
            ->object();

        return mensagemSucesso([
            'id' => $dado->dado->usuario
        ]);
    }

    public function postSenhaValidar(Request $request)
    {
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $dado = $Api
            ->validar('Ocorreu um erro ao validar seu código, por favor, tente novamente.')
            ->body([
                'usuario' => $request->usuario,
                'codigo'  => $request->codigo,
            ])
            ->post('/usuario-cliente/senha')
            ->object();

        return mensagemSucesso([
            'hash' => $dado->dado->hash
        ]);
    }

    public function postSenhaAlterar(Request $request)
    {
        $Crypt = $this->crypt();
        $Api = (new ApiHelper(scope: 'usuario_cliente:senha'));
        $Api
            ->validar('Ocorreu um erro ao atualizar sua senha, por favor, tente novamente.')
            ->body([
                'senha'   => $Crypt->encode($request->senha),
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

    private function crypt()
    {
        $Api = new ApiHelper('admin:chave_publica admin:chave_privada');
        $publica = $Api
            ->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';
        $privada = $Api
            ->get('/admin/chave-privada')
            ->object()->dado->chave ?? '';

        return new CryptHelper(chavePublica: $publica, chavePrivada: $privada);
    }
}
