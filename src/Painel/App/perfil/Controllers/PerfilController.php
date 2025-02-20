<?php

namespace PainelApp\perfil\Controllers;

use App\Classes\UsuarioEquipe\Helper;
use Controller\Controller;
use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Helpers\ListaHelper;
use Http\Request;
use Http\Response;

final class PerfilController extends Controller
{
    public function index(): Response
    {
        $Api = new ApiHelper(token: true);
        $usuario = $Api->get('/perfil-dado')->array();
        if (existeErro($usuario, 'dado')) {
            mensagemStatus(404, localhost: 'Não foi encontrado o usuário');
        }

        return view(arquivo: 'perfil.index', var: $this->descriptografarUsuario($usuario['dado']));
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

    public function dado(): Response
    {
        if (!sessaoExiste('USUARIO.id')) {
            return new Response(url: LINK . '/sair');
        }

        $id = sessao('USUARIO.id');
        $Api = new ApiHelper(token: true);
        $usuario = $Api
            ->validar('Erro ao buscar dados do seu perfil.')
            ->get('/perfil-dado')
            ->array();

        return view(arquivo: 'perfil.dado', var: [
            'appTitulo' => 'Atualizar Dados',
            'appVoltar' => [route('perfil.index'), 'Perfil'],
            'genero'    => (new ListaHelper())->add('', 'Escolha uma opção')->genero()->r(),
            'usuario'   => $this->descriptografarUsuario($usuario['dado'])
        ]);
    }

    public function postValidarSenha(Request $request)
    {
        return mensagemSucesso(['senha' => $this->validarSenha($request->senha)]);
    }

    private function validarSenha(string $senha): bool
    {
        $chave = (new ApiHelper(token: true))->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $dado = (new ApiHelper(token: true))
            ->body([
                'senha' => criptografarDado(dado: $senha, chave: $chave)
            ])
            ->post('/perfil-dado/validar-senha')
            ->object();

        $senha = $dado->dado->senha ?? false;
        return $senha == 1;
    }

    public function postDado(Request $request): Response
    {
        $this->verificarUsuarioLogadoAjax();

        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $id = sessao('USUARIO.id');
        $dado = criptografarDado(
            dado: [
                'nome'              => $request->nome,
                'data_nascimento'   => $request->data_nascimento,
                'genero'            => $request->genero,
                'email_pessoal'     => $request->email_pessoal,
                'telefone_trabalho' => soNumero($request->telefone_trabalho),
                'telefone_pessoal'  => soNumero($request->telefone_pessoal),
                'perfil'            => $request->perfil
            ],
            criptografia: [
                'nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho', 'telefone_pessoal', 'perfil'
            ],
            chave: $chave
        );

        $Api
            ->validar('Ocorreu um erro ao atualizar seus dados, por favor, tente novamente.')
            ->body($dado)
            ->put('/perfil-dado');

        return new Response(status: 204);
    }

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

    public function getSenha(): Response
    {
        return view(arquivo: 'perfil.senha');
    }

    public function postSenha(Request $request): Response
    {
        $this->verificarUsuarioLogadoAjax();

        $request
            ->vazio('senha_atual', mensagem: 'O campo senha atual é obrigatório.')
            ->vazio('senha_nova', mensagem: 'O campo nova senha é obrigatório.')
            ->vazio('senha_repetir', mensagem: 'O campo repetir nova senha é obrigatório.');

        if ($request->senha_nova != $request->senha_repetir) {
            mensagemErro('Campo inválido!', 'O campo nova senha e repetir senha estão diferentes.');
        }

        if (!$this->validarSenha($request->senha_atual)) {
            mensagemErro(
                'Senha inválida!',
                'O campo senha atual está incorreta.'
            );
        }

        $chave = (new ApiHelper(token: true))->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $id = sessao('USUARIO.id');

        (new ApiHelper(token: true))
            ->validar('Ocorreu um erro ao atualizar seus dados, por favor, tente novamente.')
            ->body([
                'senha_atual' => criptografarDado(dado: $request->senha_atual, chave: $chave),
                'senha_nova'  => criptografarDado(dado: $request->senha_nova, chave: $chave)
            ])
            ->put('/perfil-dado/atualizar-senha');

        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DE IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postImagem(Request $request)
    {
        $arquivo = $request->getFiles('arquivo');
        $dado = (new ApiHelper(token: true))
            ->validar('Erro ao fazer o upload da imagem, por favor, tente novamente.')
            ->arquivo(['imagem' => $arquivo])
            ->post('/perfil-dado/atualizar-imagem')
            ->object();

        $chave = (new ApiHelper(token: true))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);
        $imagem = $Crypt->decode($dado->dado->imagem);
        $imagem = !empty($imagem) ? imagem($imagem, 200, 200, true, true) : '';

        sessao('USUARIO.imagem', $imagem);
        return mensagemSucesso(['imagem' => $imagem], status: 201);
    }

    public function empresa()
    {
        $empresa = (new ApiHelper(token: true))
            ->json(['titulo' => 'Escolha uma empresa'])
            ->get('/comercial-empresa/select')
            ->array()['dado'] ?? [];

        return view('perfil.empresa', [
            'empresa' => $empresa
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MENSAGEM SE O USUÁRIO NÃO ESTIVER LOGADO
    |--------------------------------------------------------------------------
    */

    public function postEmpresa(Request $request)
    {
        (new ApiHelper(token: true))
            ->validar('Erro ao mudar a empresa da equipe.')
            ->body(['empresa' => $request->empresa])
            ->put('/usuario-equipe/empresa');

        return new Response(status: 204);
    }
}
