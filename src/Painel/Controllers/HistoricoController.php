<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\DataHelper;
use Controller\Controller;

final class HistoricoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    public function postSalvar(Request $request)
    {
        if (empty($request->mensagem)) {
            mensagemErro('Campo obrigatório!', 'Digite uma mensagem para seu histórico.');
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api->body([
            'relacionado' => [$request->relacionado],
            'app' => [$request->app],
            'acao' => 'mensagem',
            'mensagem' => $request->mensagem
        ])->post('/painel-historico')->object();

        if (existeErro($dado, 'dado')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao salvar seu histórico.'
            );
        }

        return mensagemSucesso([
            'id' => $dado->dado->id,
            'mensagem' => $dado->dado->mensagem
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    public function deleteDeletar(string $id)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->delete('/painel-historico/' . $id);
        return new Response(status: $dado->status());
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function getListar(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->json([
            'data_de' => $request->data_de,
            'data_ate' => $request->data_ate,
            'pagina' => $request->pagina,
            'app' => $request->app,
            'relacionado' => $request->relacionado,
        ])->get('/painel-historico')->object();

        if (existeErro($dado, 'dado')) {
            return new Response(status: 500);
        }

        return mensagemSucesso([
            'lista' => $this->montarDado($dado->dado->lista),
            'pagina' => $dado->dado->pagina->total
        ]);
    }

    private function montarDado(array $dado): array
    {
        $retorno = [];
        $dataLista = [];
        $DataHelper = new DataHelper();
        $hoje = date('Ymd');

        foreach ($dado as $r) {
            $hash = $DataHelper->valor($r->data_criacao)->formato('Ymd');
            if (!in_array($hash, $dataLista)) {
                $dataLista[] = $hash;
                $retorno[] = [
                    'tipo' => $hash == $hoje ? 'hoje' : 'data',
                    'hash' => $hash,
                    'social' => $DataHelper->valor($r->data_criacao)->social(),
                    'data' => $DataHelper->valor($r->data_criacao)->extenso()
                ];
            }
            $retorno[] = [
                'id' => $r->id,
                'tipo' => 'mensagem',
                'nome' => $r->nome,
                'imagem' => $r->imagem,
                'minha_mensagem' => $r->minha_mensagem,
                'mensagem' => nl2br($r->mensagem),
                'hora' => $DataHelper->valor($r->data_criacao)->formato('H:i')
            ];
        }
        return $retorno;
    }
}
