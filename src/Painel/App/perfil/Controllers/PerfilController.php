<?php

namespace PainelApp\perfil\Controllers;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Helpers\ListaHelper;
use Controller\Controller;
use App\Classes\UsuarioEquipe\Helper;

final class PerfilController extends Controller
{
    public function index(): Response
    {
        if (!sessaoExiste('USUARIO.id')) {
            return new Response(url: LINK . '/sair');
        }

        $id = sessao('USUARIO.id');
        $Api = new ApiHelper(token: true);
        $usuario = $Api->get('/usuario-equipe/' . $id)->array();
        if (existeErro($usuario, 'dado')) {
            mensagemStatus(404, localhost: 'Não foi encontrado o usuário');
        }

        return view(arquivo: 'perfil.index', var: $this->descriptografarUsuario($usuario['dado']));
    }

    public function dado(): Response
    {
        if (!sessaoExiste('USUARIO.id')) {
            return new Response(url: LINK . '/sair');
        }

        $id = sessao('USUARIO.id');
        $Api = new ApiHelper(token: true);
        $usuario = $Api->get('/usuario-equipe/' . $id)->array();
        if (existeErro($usuario, 'dado')) {
            mensagemStatus(404, localhost: 'Não foi encontrado o usuário');
        }

        return view(arquivo: 'perfil.dado', var: [
            'appTitulo' => 'Atualizar Dados',
            'appVoltar' => [route('perfil.index'), 'Perfil'],
            'genero' => (new ListaHelper)->add('', 'Escolha uma opção')->genero()->r(),
            'usuario' => $this->descriptografarUsuario($usuario['dado'])
        ]);
    }

    private function descriptografarUsuario($dado): array
    {

        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $crypt = new CryptHelper(chavePrivada: $chave);
        foreach ($dado as $ind => $val) {
            if (empty($val) || !in_array($ind, Helper::CRIPTOGRAFAR)) {
                continue;
            }
            $val = $crypt->decode($val);
            $dado[$ind] = $val;
        }
        return $dado;
    }

    public function postValidarSenha(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $dado = $Api->body([
            'senha' => criptografarDado($request->senha, chave: $chave)
        ])->post('/usuario-equipe/validar-senha')->object();

        if (existeErro($dado, 'dado')) {
            return mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao validar sua senha.'
            );
        }
        return mensagemSucesso(['senha' => $dado->dado->senha == 1]);
    }

    public function postDado(Request $request): Response
    {
        $this->verificarUsuarioLogadoAjax();

        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $id = sessao('USUARIO.id');
        $dado = criptografarDado(
            valor: [
                'nome' => $request->nome,
                'data_nascimento' => $request->data_nascimento,
                'genero' => $request->genero,
                'email_pessoal' => $request->email_pessoal,
                'telefone_trabalho' => soNumero($request->telefone_trabalho),
                'telefone_pessoal' => soNumero($request->telefone_pessoal),
            ],
            lista: ['nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho', 'telefone_pessoal'],
            chave: $chave
        );

        $salvar = $Api->body($dado)->put('/usuario-equipe/' . $id);
        if ($salvar->status() == 204) {
            return new Response(status: 204);
        }

        $salvar = $salvar->object();
        return mensagemErro(
            $salvar->erro->titulo ?? 'Erro!',
            $salvar->erro->mensagem ?? 'Ocorreu um erro ao validar sua senha.'
        );
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
