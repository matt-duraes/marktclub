<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiUsuario\UsuarioEntity;

final class DocumentacaoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PRECISA ESTÁ LOGADO
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }
        return view(arquivo: 'documentacao.index');
    }

    public function fluxograma()
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }
        return view(arquivo: 'documentacao.fluxograma');
    }

    public function retorno()
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }
        return view(arquivo: 'documentacao.retorno');
    }

    public function rota(string $rota)
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }

        $scope = sessao('DOCUMENTACAO.scope');
        if (empty($rota) || !in_array($rota, $scope) || !file_exists(ROOT . '/views/pages/api/documentacao/rota/include/' . $rota . '.php')) {
            mensagemStatus(404);
        }

        return view(arquivo: 'documentacao.rota', var: [
            'rota' => $rota
        ]);
    }

    public function app()
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }
        return view(arquivo: 'documentacao.app');
    }

    public function sair()
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao/login');
        }

        sessaoDeletar('DOCUMENTACAO');
        return location(LINK . '/documentacao/login');
    }

    public function postMostrarSecretId(Request $request)
    {
        $this->validarSenhaParaContinuar($request->senha);

        foreach (sessao('DOCUMENTACAO.app')->lista as $r) {
            if ($r->id == $request->id) {
                return mensagemSucesso([
                    'secret_id' => $request->tipo == 'producao' ? $r->secret_id : $r->secret_id_fake
                ]);
            }
        }

        mensagemErro(
            'Erro!',
            'Não foi encontrado um secret_id para esse APP, ele pode ter sido deletado ou você não tem permissão para visualizar.'
        );
    }
    public function postMostrarChavePublica(Request $request)
    {
        $this->validarSenhaParaContinuar($request->senha);

        foreach (sessao('DOCUMENTACAO.app')->lista as $r) {
            if ($r->id == $request->id) {
                return mensagemSucesso([
                    'chave_publica' => $request->tipo == 'producao' ? $r->chave_publica : $r->chave_publica_fake
                ]);
            }
        }

        mensagemErro(
            'Erro!',
            'Não foi encontrada uma chave pública para esse APP, ele pode ter sido deletado ou você não tem permissão para visualizar.'
        );
    }
    public function postMostrarChavePrivada(Request $request)
    {
        $this->validarSenhaParaContinuar($request->senha);

        foreach (sessao('DOCUMENTACAO.app')->lista as $r) {
            if ($r->id == $request->id) {
                return mensagemSucesso([
                    'chave_privada' => $request->tipo == 'producao' ? $r->chave_privada : $r->chave_privada_fake
                ]);
            }
        }

        mensagemErro(
            'Erro!',
            'Não foi encontrada uma chave pública para esse APP, ele pode ter sido deletado ou você não tem permissão para visualizar.'
        );
    }

    public function postResetarChavePublica(Request $request)
    {
        $Usuario = $this->validarSenhaParaContinuar($request->senha);

        $App = new AppEntity();
        $App->id($request->id, false);

        if (empty($App->id)) {
            mensagemErro('Erro!', 'O App não foi encontrado, ele pode ter sido deletado ou você não tem permissão para visualizar.', status: 404);
        }

        $App->criarChavePublica();
        $App->salvar();

        $app = $Usuario->pegarApp();
        sessao('DOCUMENTACAO', [
            'app' => $app,
            'scope' => $this->pegarTodoScopeUsuario($app),
            'usuario' => (object)[
                'id' => $Usuario->id,
                'nome' => $Usuario->nome
            ]
        ]);

        return new Response(status: 201);
    }
    private function pegarTodoScopeUsuario($app)
    {
        $lista = $app->lista;
        $scope = [];
        foreach ($lista as $r) {
            $scope = array_merge($r->scope);
        }
        $unico = array_unique($scope, SORT_STRING);
        $retorno = [];
        foreach ($unico as $val) {
            $retorno[] = str_replace(['_', ':'], '-', $val);
        }
        return $retorno;
    }

    public function postResetarSecretId(Request $request)
    {
        $Usuario = $this->validarSenhaParaContinuar($request->senha);

        $App = new AppEntity();
        $App->id($request->id, false);

        if (empty($App->id)) {
            mensagemErro('Erro!', 'O App não foi encontrado, ele pode ter sido deletado ou você não tem permissão para visualizar.', status: 404);
        }

        $App->criarSecretId();
        $App->criarClientId();
        $App->salvar();

        $app = $Usuario->pegarApp();
        sessao('DOCUMENTACAO', [
            'app' => $app,
            'scope' => $this->pegarTodoScopeUsuario($app),
            'usuario' => (object)[
                'id' => $Usuario->id,
                'nome' => $Usuario->nome
            ]
        ]);

        return new Response(status: 201);
    }

    private function validarSenhaParaContinuar(string $senha)
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            $this->mensagemSemSessao();
        } else if (empty($senha)) {
            mensagemErro('Campo obrigatório!', 'Você precisa enviar a sua senha atual para continuar.');
        }

        $Usuario = new UsuarioEntity;
        $Usuario->id(sessao('DOCUMENTACAO.usuario')->id);

        if (!$Usuario->senha->validarSenha($senha)) {
            mensagemErro('Senha inválida!', 'A senha digitada não está correta.');
        }
        return $Usuario;
    }

    public function postSenha(Request $request)
    {
        if (!sessaoExiste('DOCUMENTACAO')) {
            return $this->mensagemSemSessao();
        } else if (empty($request->senha_atual)) {
            mensagemErro('Campo obrigatório!', 'Você precisa enviar a sua senha atual para continuar.');
        } else if (empty($request->senha_nova)) {
            mensagemErro('Campo obrigatório!', 'Você precisa enviar a sua nova senha para continuar.');
        } else if ($request->senha_nova != $request->senha_repetir) {
            mensagemErro('Campo obrigatório!', 'O campo nova senha e repetir senha não estão iguais.');
        }

        $Usuario = new UsuarioEntity;
        $Usuario->id(sessao('DOCUMENTACAO.usuario')->id);

        if (!$Usuario->senha->validarSenha($request->senha_atual)) {
            mensagemErro('Senha inválida!', 'A senha atual digitada não está correta.');
        }

        $Usuario->senha->mudarSenha($request->senha_nova);
        $Usuario->salvar();

        return new Response(status: 204);
    }

    public function mensagemSemSessao()
    {
        mensagemErro('Sessão expirou!', 'Sua sessão foi finalizada, por favor, refaça seu login para continuar.', 401);
    }

    /*
    |--------------------------------------------------------------------------
    | NÃO PODE ESTÁ LOGADO
    |--------------------------------------------------------------------------
    */
    public function login()
    {
        if (sessaoExiste('DOCUMENTACAO')) {
            return location(LINK . '/documentacao');
        }
        return view(arquivo: 'documentacao.login');
    }
    public function postLogin(Request $request)
    {
        if (sessaoExiste('DOCUMENTACAO')) {
            return mensagemSucesso([], status: 201);
        } else if (empty($request->login)) {
            mensagemErro('Campo obrigatório!', 'Você deve digitar seu login para continuar.');
        } else if (empty($request->senha)) {
            mensagemErro('Campo obrigatório!', 'Você deve digitar sua senha para continuar.');
        }

        $Usuario = new UsuarioEntity;
        $Usuario->buscar(['login_usuario', $request->login], false);
        if (empty($Usuario->id)) {
            usleep(rand(500000, 1000000));
            mensagemErro('Erro!', 'Seu login e/ou senha estão inválidos');
        } else if (!$Usuario->senha->validarSenha($request->senha)) {
            usleep(rand(300000, 800000));
            mensagemErro('Erro!', 'Seu login e/ou senha estão inválidos');
        }

        $app = $Usuario->pegarApp();
        sessao('DOCUMENTACAO', [
            'app' => $Usuario->pegarApp(),
            'scope' => $this->pegarTodoScopeUsuario($app),
            'usuario' => (object)[
                'id' => $Usuario->id,
                'nome' => $Usuario->nome
            ]
        ]);
        return mensagemSucesso([], status: 201);
    }
}
