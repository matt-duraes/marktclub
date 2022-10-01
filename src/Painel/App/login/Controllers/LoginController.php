<?php

namespace PainelApp\login\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\JwtHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;
use Helpers\SocialHelper;
use Controller\Controller;

final class LoginController extends Controller
{
    private string $chavePublica;
    private string $chavePrivada;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view(arquivo: 'login.index');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGAR COM FORMULÁRIO
    |--------------------------------------------------------------------------
    */
    public function postLogin(Request $request): Response
    {
        $this->setarChave();
        $body = criptografarDado([
            'login' => $request->login,
            'senha' => $request->senha,
            'scope' => '',
            'audience' => env('API_AUDIENCE', ''),
            'redirect_uri' => env('API_REDIRECT_URI', ''),
            'state' => uuid()
        ], lista: ['login', 'senha'], chave: $this->chavePublica);

        return $this->enviarDadosParaLogin($body);
    }

    private function enviarDadosParaLogin($body)
    {
        $Api = new ApiHelper('login:painel');
        $dado = $Api->body($body)->post('/login/painel')->object();

        $this->autenticarUsuario($dado);

        return mensagemSucesso([
            'link' => (new AuthHelper)->location()
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN SOCIAL
    |--------------------------------------------------------------------------
    */
    public function postSocial(Request $request)
    {
        $this->setarChave();

        $Social = new SocialHelper(
            rede: $request->rede,
            id: $request->id,
            token: $request->token,
            code: $request->code
        );

        $body = criptografarDado([
            $request->rede => $Social->id(),
            'scope' => '',
            'audience' => env('API_AUDIENCE', ''),
            'redirect_uri' => env('API_REDIRECT_URI', ''),
            'state' => uuid()
        ], lista: ['facebook', 'google'], chave: $this->chavePublica);

        return $this->enviarDadosParaLogin($body);
    }

    /*
    |--------------------------------------------------------------------------
    | DESBLOQUEAR
    |--------------------------------------------------------------------------
    */
    // public function bloquear(): Response
    // {
    //     (new AuthHelper)->deletar();
    //     return new Response(status: 200);
    // }
    // public function postDesbloquear(Request $request): Response
    // {
    //     new LoginFormModel($request);
    //     return new Response(status: 201);
    // }

    private function autenticarUsuario($dado): bool
    {
        if (!is_object($dado) || !object_key_exists('status', $dado)) {
            mensagemErro(
                'Erro no login!',
                'Ocorreu um erro ao fazer seu login, por favor, tente novamente.',
                status: 500,
                localhost: 'Erro no retorno da API.'
            );
        } else if ($dado->status != 'sucesso') {
            mensagemErro(
                titulo: $dado->erro->titulo ?? 'Erro!',
                mensagem: $dado->erro->mensagem ?? 'Retorno não tem status de sucesso.'
            );
        }

        $token = $dado->dado;

        $Jwt = new JwtHelper();
        $body = $Jwt->decode($token->id_token);

        $this->setarChave();
        $Crypt = new CryptHelper(chavePrivada: $this->chavePrivada);
        (new AuthHelper)->criar([
            'id' => $body['sub'],
            'nome' => $Crypt->decode($body['name']),
            'email' => $Crypt->decode($body['email']),
            'imagem' => $Crypt->decode($body['picture']),
            'cpf' => $Crypt->decode($body['document']),
            'permissao' => $body['permission'],
            'empresa_id' => $body['company_id'],
            'dev' => in_array($body['document'], jsonDecode(env('DEV_DOCUMENTO', []), true, true))
        ]);

        sessao('TOKEN', $token->access_token);

        if (object_key_exists('refresh_token', $token)) {
            criarCookie('REFRESH_TOKEN', base64Encode($token->refresh_token, 'hash_refresh_token'));
        }

        $this->pegandoPermissaoDoPainel();
        $this->pegandoCampoObrigatorio();
        $this->pegandoConfiguracaoDoPainel();
        $this->pegandoCampoPermitidos();
        $this->pegandoListaMenu();
        return true;
    }

    private function pegandoPermissaoDoPainel()
    {
        $Api = new ApiHelper(token: true);

        $permissaoMontar = $Api->headerJson()->get('/admin/permissao')->array();
        $permissaoMontar = array_key_exists('dado', $permissaoMontar) ? $permissaoMontar['dado'] : [];
        $permissaoLista = [];
        foreach ($permissaoMontar as $ind => $val) {
            if (array_key_exists('acao', $val)) {
                foreach ($val['acao'] as $acao) {
                    $permissaoLista[] = $ind . '_' . $acao;
                }
            } else if (array_key_exists('permissao', $val)) {
                foreach (array_keys($val['permissao']) as $acao) {
                    $permissaoLista[] = $acao;
                }
            }
        }

        sessao('PAINEL.permissao.montar', $permissaoMontar);
        sessao('PAINEL.permissao.lista', $permissaoLista);
    }

    private function pegandoCampoPermitidos()
    {
        $Api = new ApiHelper(token: true);
        $campo = $Api->headerJson()->get('/admin/campo-permitido')->array();
        $campo = array_key_exists('dado', $campo) ? $campo['dado'] : [
            "usuario_cliente" => [
                'nome', 'cpf', 'matricula', 'siape', 'genero', 'data_nascimento',
                'email', 'telefone', 'endereco_estado',
                'endereco_cidade', 'senha', 'status', 'primeiro_acesso', 'mudar_senha', 'estado_civil'
            ]
        ];

        sessao('PAINEL.campo', $campo);
    }

    private function pegandoCampoObrigatorio()
    {
        $Api = new ApiHelper(token: true);
        $obrigatorio = $Api->headerJson()->get('/admin/campo-obrigatorio')->array();
        $obrigatorio = array_key_exists('dado', $obrigatorio) ? $obrigatorio['dado'] : [
            "usuario_cliente" => [
                "cpf",
                "email",
                "status"
            ]
        ];

        sessao('PAINEL.obrigatorio', $obrigatorio);
    }

    private function pegandoConfiguracaoDoPainel()
    {
        $Api = new ApiHelper(token: true);
        $configuracao = $Api->headerJson()->get('/admin/configuracao')->array();
        $configuracao = array_key_exists('dado', $configuracao) ? $configuracao['dado'] : ["perfil", "bloquear"];

        sessao('PAINEL.configuracao', $configuracao);
    }

    private function pegandoListaMenu()
    {
        $Api = new ApiHelper(token: true);
        $menu = $Api->headerJson()->get('/admin/menu')->array();
        $menu = array_key_exists('dado', $menu) ? $menu['dado'] : [];
        sessao('PAINEL.menu', $menu);
    }

    /*
    |--------------------------------------------------------------------------
    | SAIR
    |--------------------------------------------------------------------------
    */
    public function sair(): Response
    {
        (new AuthHelper)->deletar();
        sessaoDeletar('relatorio');
        sessaoDeletar('TOKEN');
        return new Response(url: route('login.index'));
    }

    public function setarChave()
    {
        $Api = new ApiHelper('admin:chave_publica admin:chave_privada');
        $this->chavePublica = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $this->chavePrivada = $Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
    }
}
