<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Classes\PainelNotificacao\Status;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use ApiModel\PainelNotificacao\NotificacaoModel;
use ApiModel\PainelNotificacao\NotificacaoEntity;
use System\Interface\ControllerAtualizarInterface;
use ApiModel\PainelNotificacao\VisualizarTodasModel;

final class PainelNotificacaoController extends Controller implements
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface
{
    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    public function getBuscar(string $id): Response
    {
        $Notificacao = new NotificacaoEntity();
        $Notificacao->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Notificacao,
                lista: [
                    'id', 'dono', 'titulo', 'mensagem', 'link', 'botao', 'target', 'status'
                ]
            )
        );
    }
    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function getListar(Request $request): Response
    {
        $Notificacao = new NotificacaoModel($request);
        return mensagemSucesso($Notificacao->listarDados());
    }
    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    public function postSalvar(Request $request): Response
    {
        $request
            ->vazio('titulo', mensagem: 'O campo título é obrigatório.')
            ->vazio('mensagem', mensagem: 'O campo mensagem é obrigatório.')
            ->vazio('dono', mensagem: 'O campo dono é obrigatório.');

        $Notificacao = new NotificacaoEntity(
            titulo: $request->titulo,
            mensagem: $request->mensagem,
            link: $request->link,
            botao: $request->botao,
            Equipe: $this->pegarUsuario(
                empty($request->equipe) ? $request->dono : $request->equipe,
                'Não foi possível achar o usuário da notificação.'
            ),
            Dono: $this->pegarUsuario($request->dono, 'Não foi possível achar o dono da notificação.')
        );
        $Notificacao->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Notificacao, lista: [
                'id', 'titulo', 'mensagem'
            ]),
            201
        );
    }
    private function pegarUsuario(string $id, string $mensagem): EquipeEntity
    {
        try {
            $Dono = new EquipeEntity();
            $Dono->id($id);
        } catch (\Throwable) {
            mensagemErro('Erro!', $mensagem);
        }
        return $Dono;
    }

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR
    |--------------------------------------------------------------------------
    */
    public function putAtualizar(Request $request, string $id): Response
    {
        $status = new Status($request->status);
        if (!$status->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar um status válido.');
        }

        $Notificacao = new NotificacaoEntity();
        $Notificacao->id($id);
        $Notificacao->status = $status;
        $Notificacao->salvar();

        return new Response(status: 204);
    }

    public function putVisualizarTodas()
    {
        $Noficacao = new VisualizarTodasModel();
        $Noficacao->visualizarTodas();
        return new Response(status: 204);
    }
}
