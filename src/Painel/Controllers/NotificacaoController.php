<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use PainelModel\Notificacao\HelperModel;

final class NotificacaoController extends Controller
{
    private ApiHelper $Api;

    public function __construct()
    {
        $this->Api = new ApiHelper(token: true);
        parent::__construct();
    }

    public function getListar(Request $request)
    {
        $notificacao = $this
            ->Api
            ->validar('Erro ao buscar lista de notificações')
            ->json([
                'novo'       => 'nao',
                'pagina'     => $request->pagina,
                'quantidade' => 20
            ])
            ->get('/painel-notificacao')->object();

        $notificacao->dado->lista = (new HelperModel())->tratarRetorno($notificacao->dado->lista);
        return mensagemSucesso($notificacao->dado);
    }

    public function postAtualizar(Request $request)
    {
        foreach ($request->id as $id) {
            $this
                ->Api
                ->validar('Ocorreu um erro ao mudar o status de uma ou mais notificações.')
                ->body([
                    'status' => 'visualizado'
                ])
                ->put('/painel-notificacao/' . $id);
        }

        return new Response(status: 204);
    }

    public function abrir(string $id)
    {
        $dado = $this
            ->Api
            ->validar('Ocorreu um erro ao abrir URL')
            ->get('/painel-notificacao/' . $id)->object();

        $this
            ->Api
            ->validar('Erro ao mudar status da notificação')
            ->body([
                'status' => 'clicado'
            ])
            ->put('/painel-notificacao/' . $id);

        return new Response(url: str_replace('{{LINK}}', LINK, $dado->dado->link));
    }

    public function getVisualizarTodas()
    {
        $this
            ->Api
            ->validar('Erro ao mudar status da notificação')
            ->put('/painel-notificacao/visualizar-todas');

        return new Response(status: 204);
    }
}
