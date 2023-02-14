<?php

namespace PainelApp\perfil\Controllers;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Helpers\ListaHelper;
use Helpers\SocialHelper;
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
        $usuario = $Api
            ->validar('Erro ao buscar dados do seu perfil.')
            ->get('/usuario-equipe/' . $id)
            ->array();

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
                'perfil' => $request->perfil
            ],
            lista: ['nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho', 'telefone_pessoal', 'perfil'],
            chave: $chave
        );

        $Api
            ->validar('Ocorreu um erro ao atualizar seus dados, por favor, tente novamente.')
            ->body($dado)
            ->put('/usuario-equipe/' . $id);

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

        $Social = new SocialHelper(
            rede: $request->rede,
            id: $request->id,
            token: $request->token,
            code: $request->code
        );

        if ($request->acao == 'imagem') {
            return $this->vincularImagem($Social, $request->rede);
        }

        $campo = $request->rede == 'google' ? 'id_google' : 'id_facebook';
        $dado = [$campo => $Social->id()];

        $this->atualizarDadoDaEquipe($dado);

        sessao('USUARIO.' . $request->rede, $Social->id());
        return mensagemSucesso([], status: 201);
    }

    private function vincularImagem(SocialHelper $Social, $rede)
    {
        if ($rede == 'google') {
            $imagem = $Social->imagem();
            $campo = 'imagem_google';
        } else if ($rede == 'facebook') {
            $imagem = $Social->imagem();
            $campo = 'imagem_facebook';
        }

        $this->atualizarDadoDaEquipe([
            $campo => $imagem
        ]);

        sessao('USUARIO.imagem', $imagem);

        return mensagemSucesso([
            'imagem' => $imagem
        ], status: 201);
    }

    private function atualizarDadoDaEquipe($dado)
    {
        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $dado = criptografarDado(
            valor: $dado,
            lista: ['imagem_google', 'imagem_facebook', 'id_google', 'id_facebook'],
            chave: $chave
        );

        $status = $Api->body($dado)->put('/usuario-equipe/' . sessao('USUARIO.id'))->status();

        if ($status == 204) {
            return;
        }

        mensagemErro('Erro!', 'Ocorreu um erro ao tentar salvar as informações, por favor, tente novamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DE IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postImagem(Request $request)
    {
        $arquivo = $request->_FILES('arquivo');
        $dado = (new ApiHelper(token: true))
            ->validar('Erro ao fazer o upload da imagem, por favor, tente novamente.')
            ->body(['id' => sessao('USUARIO.id')])
            ->arquivo(['imagem' => $arquivo])
            ->post('/usuario-equipe/imagem')
            ->object();

        $chave = (new ApiHelper(token: true))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);
        $imagem = $Crypt->decode($dado->dado->imagem);

        sessao('USUARIO.imagem', $imagem);
        return mensagemSucesso(['imagem' => $imagem . '?cache=' . md5(uniqid(time()))], status: 201);
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
