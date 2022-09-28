<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Painel\Demanda\Models\TarefaModel;
use Painel\Demanda\Models\TarefaEntity;
use Painel\Demanda\Models\ArquivoEntity;
use Painel\Demanda\Models\MensagemEntity;

final class DemandaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX/DETALHE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $Tarefa = new TarefaModel();
        return view('painel.demanda.index', [
            'tarefa' => [
                $Tarefa->backlog(),
                $Tarefa->toDo(),
                $Tarefa->progresso(),
                $Tarefa->review(),
                $Tarefa->teste(),
                $Tarefa->deploy()
            ]
        ]);
    }

    public function detalhe($url)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->buscar(['url', $url]);

        return view('painel.demanda.detalhe', [
            'id' => $Tarefa->id,
            'titulo' => $Tarefa->titulo,
            'texto' => $Tarefa->texto,
            'tipo' => $Tarefa->get('tipo'),
            'prioridade' => $Tarefa->get('prioridade'),
            'criado' => $Tarefa->get('criado'),
            'entrega' => $Tarefa->get('entrega'),
            'status' => $Tarefa->get('status'),
            'equipe' => $Tarefa->get('equipe'),
            'seguindo' => $Tarefa->get('seguindo'),
            'seguir' => $Tarefa->get('seguir'),
            'usuario' => (object)[
                'id' => sessao('USUARIO.uuid'),
                'nome' => sessao('USUARIO.nome'),
                'imagem' => sessao('USUARIO.imagem')
            ],
            'arquivo' => html('painel.demanda.arquivo', ['lista' => $Tarefa->pegarArquivo()]),
            'mensagem' => $Tarefa->pegarMensagem()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ARQUIVO
    |--------------------------------------------------------------------------
    */
    public function postUpload(Request $request)
    {
        $Arquivo = new ArquivoEntity(
            tarefa: $request->chave('demanda'),
            upload: $request->_FILES('arquivo')
        );
        $Arquivo->salvar();

        $html = html('painel.demanda.arquivo', [
            'upload' => true,
            'tipo' => $Arquivo->tipo,
            'nome' => $Arquivo->nome,
            'extensao' => strCaixaAlta($Arquivo->extensao),
            'link' => $Arquivo->link
        ]);

        return new Response(json: [
            'id' => $Arquivo->id,
            'nome' => $Arquivo->nome,
            'mensagem' => $Arquivo->pegarMensagem(),
            'html' => $html
        ], status: 201);
    }

    public function putArquivo(Request $request)
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($request->id);
        $Arquivo->nome = $request->nome;
        $Arquivo->salvar();

        return new Response(json: [
            'mensagem' => $Arquivo->pegarMensagem()
        ], status: 201);
    }
    public function deleteArquivo(string $uuid)
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($uuid);
        $Arquivo->destruir();

        return new Response(json: [
            'mensagem' => $Arquivo->pegarMensagem()
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SEGUIDOR
    |--------------------------------------------------------------------------
    */
    public function postSeguir(Request $request)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($request->id);
        $Tarefa->seguirOuSairDaTarefa($request->acao);
        $Tarefa->salvar();

        return new Response(json: [
            'id' => sessao('USUARIO.uuid'),
            'nome' => sessao('USUARIO.nome'),
            'imagem' => sessao('USUARIO.imagem'),
            'mensagem' => $Tarefa->pegarMensagemNova(),
        ], status: 201);
    }

    public function postSeguidores(Request $request)
    {
        $id = $request->seguidor;
        $Tarefa = new TarefaEntity();
        $Tarefa->id($request->id);
        $Tarefa->adicionarListaSeguidores($id ? $id : []);
        $Tarefa->salvar();

        return new Response(json: [
            'mensagem' => $Tarefa->pegarMensagemNova(),
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | MENSAGEM
    |--------------------------------------------------------------------------
    */
    public function postMensagem(Request $request)
    {
        $id = $request->id;
        $texto = $request->texto;
        $Mensagem = new MensagemEntity(
            tarefa: $id,
            texto: $texto
        );
        $Mensagem->salvar();

        return new Response(json: [
            'texto' => $Mensagem->texto
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    public function salvar()
    {
        return view('painel.demanda.salvar');
    }

    public function editar(string $uuid)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($uuid);

        return view('painel.demanda.salvar');
    }

    public function postSalvar(Request $request, ?string $uuid = null)
    {
        $Tarefa = new TarefaEntity();
        if (!empty($uuid)) {
            $Tarefa->id($uuid);
        }

        $Tarefa->set(lista: $request->lista([
            'titulo', 'texto', 'tipo'
        ]));

        $Tarefa->salvar();

        return new Response(status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | ARQUIVAR
    |--------------------------------------------------------------------------
    */
    public function putArquivar(Request $request)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($request->id);
        $Tarefa->arquivar();
        $Tarefa->salvar();

        return new Response(status: 204);
    }
}
