<?php

namespace Painel\Perfil\Controllers;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\ListaHelper;
use Helpers\SocialHelper;
use Controller\Controller;

final class PerfilController extends Controller
{
    public function index(): Response
    {
        if (!sessaoExiste('USUARIO.id')) {
            return new Response(url: LINK . '/sair');
        }

        $id = sessao('USUARIO.id');
        $Api = new ApiHelper(token: true);
        $usuario = $Api->get('/usuario-equipe/' . $id)->object();
        if (existeErro($usuario, 'dado')) {
            mensagemStatus(404, localhost: 'Não foi encontrado o usuário');
        }

        $usuario = $usuario->dado;

        return view(arquivo: 'perfil.Views.index', var: [
            'nome' => $usuario->nome,
            'cpf' => $usuario->cpf,
            'data' => $usuario->data_nascimento,
            'genero' => $usuario->genero,
            'email_pessoal' => $usuario->email_pessoal,
            'email_trabalho' => $usuario->email_trabalho,
            'telefone_pessoal' => $usuario->telefone_pessoal,
            'telefone_trabalho' => $usuario->telefone_trabalho
        ]);
    }

    public function dado(): Response
    {
        if (!sessaoExiste('USUARIO.id')) {
            return new Response(url: LINK . '/sair');
        }

        $id = sessao('USUARIO.id');
        $Api = new ApiHelper(token: true);
        $usuario = $Api->get('/usuario-equipe/' . $id)->object();
        if (existeErro($usuario, 'dado')) {
            mensagemStatus(404, localhost: 'Não foi encontrado o usuário');
        }

        $usuario = $usuario->dado;

        return view(arquivo: 'perfil.Views.dado', var: [
            'appTitulo' => 'Atualizar Dados',
            'appVoltar' => [route('perfil.index'), 'Perfil'],
            'genero' => (new ListaHelper)->add('', 'Escolha uma opção')->genero()->r(),
            'usuario' => $usuario
        ]);
    }

    public function postValidarSenha(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->body([
            'senha' => $request->senha
        ])->post('/usuario-equipe/validar-senha')->object();

        if (existeErro($dado, 'dado')) {
            return mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao validar sua senha.'
            );
        }
        return mensagemSucesso([]);
    }

    public function postDado(Request $request): Response
    {
        $this->verificarUsuarioLogadoAjax();
        return new Response(status: 204);
    }

    public function senha(): Response
    {
        return view(arquivo: 'perfil.senha');
    }

    public function postSenha(Request $request): Response
    {
        $this->verificarUsuarioLogadoAjax();
        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | VINCULANDO CONTA SOCIAL
    |--------------------------------------------------------------------------
    */
    public function postSocial(Request $request)
    {
        $this->verificarUsuarioLogadoAjax();

        // $Entity = new UsuarioEntity;
        // $Entity->id(sessao('USUARIO.id'));

        // $tipo = $request->tipo;
        // $token = $request->token;
        // $id = $request->id;

        // $Social = new SocialHelper;
        // if (
        //     ($tipo == 'google' && !$Social->google()->validarToken($token)) ||
        //     ($tipo == 'facebook' && !$Social->facebook()->validarToken($token)) ||
        //     !in_array($tipo, ['google', 'facebook'])
        // ) {
        //     throw new Excecao(
        //         titulo: 'Erro ao vincular!',
        //         mensagem: 'Não foi possível validar o token enviado, por favor, tente novamente.'
        //     );
        // }

        // $imagem = '';
        // if ($tipo == 'google') {
        //     $imagem = $Social->google()->imagem(id: $id, token: $token);
        //     $Entity->imagem_tipo = 2;
        //     $Entity->id_google = $id;
        //     $Entity->imagem_google = $imagem;
        // } elseif ($tipo == 'facebook') {
        //     $imagem = $Social->facebook()->imagem(id: $id, token: $token);
        //     $Entity->imagem_tipo = 3;
        //     $Entity->id_facebook = $id;
        //     $Entity->imagem_facebook = $imagem;
        // }

        // $Entity->salvar();
        // sessao('USUARIO.imagem', $imagem);

        return new Response(json: [
            // 'imagem' => $imagem
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | MENSAGEM SE O USUÁRIO NÃO ESTIVER LOGADO
    |--------------------------------------------------------------------------
    */
    private function verificarUsuarioLogadoAjax()
    {
        if (!sessaoExiste('USUARIO.id')) {
            throw new Excecao(
                titulo: 'Usuário deslogado!',
                mensagem: 'Seu usuário foi deslogado, refaça seu login pra continuar.',
                status: 401
            );
        }
    }
}
