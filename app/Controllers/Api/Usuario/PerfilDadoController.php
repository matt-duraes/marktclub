<?php

namespace App\Controllers\Api\Usuario;

use App\Controllers\Api\Usuario\Trait\PerfilInitTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use Controller\Controller;
use Http\Request;
use Http\Response;

final class PerfilDadoController extends Controller
{
    use PerfilInitTrait;

    private int $idUsuario;
    private string $uuidUsuario;
    private ClienteController|EquipeController $Controller;
    private ClienteEntity|EquipeEntity $Entity;

    public function __construct()
    {
        $this->validarToken();
        $this->setarController();
        $this->setarEntity();
        $this->setarIdUsuario();
    }

    public function getBuscar(): Response
    {
        return $this->Controller->getBuscar($this->uuidUsuario);
    }

    public function putAtualizar(Request $request): Response
    {
        $this->Controller->putAtualizar($request, $this->uuidUsuario);
        return new Response(status: 204);
    }

    // COMPARTILHADO
    public function postAtualizarImagem(Request $request): Response
    {
        $Usuario = $this->Entity;
        $Usuario->id($this->idUsuario);
        $Usuario->imagem_arquivo = $request->getFiles('imagem');
        $Usuario->salvar();

        return mensagemSucesso([
            'id'     => $Usuario->id,
            'imagem' => $Usuario->imagem
        ], status: 201, criptografar: ['imagem']);
    }

    public function putAtualizarSenha(Request $request): Response
    {
        $Usuario = $this->Entity;
        $Usuario->id($this->idUsuario);

        if (!$Usuario->senha->validarSenha($request->senha_atual)) {
            mensagemErro('Senha inválida!', 'A senha atual informada é inválida.');
        }

        $Usuario->senha->mudarSenha($request->senha_nova);
        $Usuario->salvar();

        return new Response(status: 204);
    }

    public function postValidarSenha(Request $request): Response
    {
        $senha = $request->senha;
        if (empty($senha)) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        }

        $Usuario = $this->Entity;
        $Usuario->buscar([
            ['id', $this->idUsuario],
            ['status', 1]
        ]);

        if ($Usuario->senha->validarSenha($request->senha)) {
            return mensagemSucesso(['senha' => 1]);
        }
        mensagemErro('Senha inválida!', 'Verifique a senha digitada e tente novamente.');
    }
}
