<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\DataHelper;
use Controller\Controller;

final class HistoricoController extends Controller
{
    public function index($app, $id)
    {
        return view('painel.historico.index', [
            'app' => $app,
            'id'  => $id
        ]);
    }

    public function postSalvar(Request $request)
    {
        if (empty($request->mensagem)) {
            mensagemErro('Campo obrigatório!', 'Digite uma mensagem para seu histórico.');
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao salvar seu histórico.')
            ->body([
                'relacionado'      => [$request->relacionado],
                'app'              => [$request->app],
                'acao'             => 'mensagem',
                'mensagem'         => $request->mensagem,
                'notificar_titulo' => base64Decode($request->titulo),
                'notificar_link'   => base64Decode($request->link),
                'notificar_equipe' => base64Decode($request->notificar)
            ])
            ->post('/painel-historico')
            ->object();

        return mensagemSucesso([
            'id'       => $dado->dado->id,
            'mensagem' => $dado->dado->mensagem
        ], status: 201);
    }

    public function deleteDeletar(string $id)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->delete('/painel-historico/' . $id);
        return new Response(status: $dado->status());
    }

    public function getListar(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Erro ao buscar lista de histórico')
            ->json([
                'data_de'     => $request->data_de,
                'data_ate'    => $request->data_ate,
                'pagina'      => $request->pagina,
                'app'         => $request->app,
                'relacionado' => $request->relacionado,
                'pesquisa'    => $request->pesquisa
            ])
            ->get('/painel-historico')
            ->object();

        return mensagemSucesso([
            'lista'  => $this->montarDado($dado->dado->lista),
            'pagina' => $dado->dado->pagina->total
        ]);
    }

    public function postDownload(Request $request)
    {
        $Api = new ApiHelper(token: true);

        $payload = [
            'campo' => [
                'usuario_nome',
                'mensagem',
                'data_criacao',
                'parceiro_nome'
            ],
            'app'           => 'painel_historico',
            'usuario'       => sessao('USUARIO.id'),
            'data_de'       => $request->data_de,
            'data_ate'      => $request->data_ate,
            'relacionado'   => $request->relacionado,
            'historico_app' => $request->app,
            'pesquisa'      => $request->pesquisa
        ];
        $dado = $Api
            ->validar('Ocorreu um erro ao salvar o seu pedido, por favor, tente novamente.')
            ->body([
                'payload' => base64Encode($payload),
                'tipo'    => 'download.privado'
            ])
            ->post('/mensageria')
            ->array();

        return mensagemSucesso([
            'id' => $dado['dado']['id']
        ], 201);
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
                    'tipo'   => $hash == $hoje ? 'hoje' : 'data',
                    'hash'   => $hash,
                    'social' => $DataHelper->valor($r->data_criacao)->social(),
                    'data'   => $DataHelper->valor($r->data_criacao)->extenso()
                ];
            }
            $mensagem = preg_replace(
                "/((https?:\/\/)[a-zA-Z\.\:0-9\/\-\_\?\=\&]{1,})/",
                '<a href="' . LINK . '/app/redirecionar?url=$0" target="_blank" rel="noopener noreferrer">$0</a>',
                $r->mensagem
            );
            $retorno[] = [
                'id'             => $r->id,
                'tipo'           => 'mensagem',
                'nome'           => $r->nome,
                'imagem'         => $r->imagem,
                'minha_mensagem' => $r->minha_mensagem,
                'mensagem'       => nl2br($mensagem),
                'hora'           => $DataHelper->valor($r->data_criacao)->formato('H:i')
            ];
        }
        return $retorno;
    }
}
