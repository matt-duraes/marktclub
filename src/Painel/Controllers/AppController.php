<?php

namespace PainelController;

use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;

final class AppController extends PadraoController
{
    public function index(Request $request, string $app): Response
    {
        $appReal = $this->converterNomeApp($app);
        if (is_dir(ROOT . '/views/pages/painel/' . $appReal . '/routes')) {
            mensagemStatus(404, localhost: 'Esse APP tem um Route próprio.');
        }

        $config = $this->config($appReal, 'index');
        if (!$config->permissao->index) {
            mensagemStatus(status: 403, localhost: 'O config do APP proibe o acesso a index.');
        }

        $pesquisa = $request->existe('pesquisa') && !empty($request->pesquisa) ? base64Decode($request->pesquisa, 'pesquisa') : '';
        $filtro = $request->existe('filtro')  && !empty($request->filtro) ? base64Decode($request->filtro, 'filtro') : [];
        $ordem = $request->existe('ordem') && !empty($request->ordem) ? base64Decode($request->ordem, 'ordem') : '';
        $pagina = $request->existe('pagina') && !empty($request->pagina) ? $request->pagina : 1;
        $pagina = preg_match('/[0-9]+/', $pagina) && $pagina > 0 ? $pagina : 1;

        $parametro = [
            'pagina' => $pagina
        ];
        if (!empty($pesquisa)) {
            $parametro['pesquisa'] = $pesquisa;
        }
        if (!empty($ordem)) {
            $parametro['ordem'] = $ordem;
        }
        if (!empty($filtro) && is_array($filtro)) {
            $parametro = array_merge($parametro, $filtro);
        }

        $parametro = $this->criptografarListaDado($parametro, array_keys($parametro), $config->api->criptografar);

        $dado = (new ApiHelper(token: true))->json($parametro)->get($config->api->uri);
        $dado = $this->validarRetornoApi($dado, true);
        if ($dado instanceof Response) {
            return $dado;
        }
        $dado->dado->lista = $this->tratarListaDeRetorno($dado->dado->lista, $config->api->criptografar);

        return view(
            arquivo: $config->index->app . '.index',
            var: [
                'dado' => $dado->dado ?? [],
                'app' => $app,
                'config' => $config,
                'acao' => 'index',
                'filtro' => $this->pegarFiltro($request, $config),
                'busca' => (object)[
                    'filtro' => $request->chave('filtro', ''),
                    'pesquisa' => $request->chave('pesquisa', ''),
                    'ordem' => $request->chave('ordem', '')
                ]
            ]
        );
    }

    public function postAjax(Request $request, string $app)
    {
        $appReal = $this->converterNomeApp($app);
        if (is_dir(ROOT . '/views/pages/painel/' . $appReal . '/routes')) {
            mensagemStatus(404, localhost: 'Esse APP tem um Route próprio.');
        }

        $config = $this->config($appReal, 'ajax');
        if (!$config->permissao) {
            mensagemStatus(status: 403, localhost: 'O config do APP proibe o acesso a index.');
        }

        $metodo = $config->metodo;
        if (!in_array($metodo, ['get', 'post', 'put'])) {
            mensagemStatus(400);
        }

        $dado = $request->dado();
        if ($dado) {
            foreach (array_keys($dado) as $ind) {
                if (!in_array($ind, $config->request)) {
                    mensagemStatus(400);
                }
            }
        }

        $Api = (new ApiHelper(token: true))->headerJson();
        if ($dado && $metodo == 'get') {
            $Api->json($dado);
        } else if ($dado) {
            $Api->body($dado);
        }
        $dado = $Api->$metodo($config->rota);

        $dado = $this->validarRetornoApi($dado);
        if ($dado instanceof Response) {
            return $dado;
        }

        return new Response(json: $Api->object(), status: $Api->status());
    }

    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR
    |--------------------------------------------------------------------------
    */
    public function visualizar(string $app, string $uuid)
    {
        $appReal = $this->converterNomeApp($app);
        if (is_dir(ROOT . '/views/pages/painel/' . $appReal . '/routes')) {
            throw new Excecao(status: 404);
        }

        $config = $this->config($appReal, 'visualizar');

        if (!$config->permissao->visualizar) {
            throw new Excecao(status: 403);
        }

        $dado = (new ApiHelper(token: true))->get($config->api->uri . '/' . $uuid);
        if ($dado->status() == 404) {
            mensagemStatus(404);
        }

        $dado = $this->validarRetornoApi($dado);
        if ($dado instanceof Response) {
            return $dado;
        }

        $retorno = $this->tratarListaDeRetorno($dado->dado, $config->api->criptografar);

        return view(
            arquivo: $config->visualizar->app . '.visualizar',
            var: [
                'app' => $app,
                'config' => $config,
                'acao' => 'visualizar',
                'dado' => is_array($retorno) ? object($retorno) : $retorno
            ],
            css: $config->visualizar->css,
            js: $config->visualizar->js,
        );
    }
    public function postStatus(Request $request)
    {
        $appReal = $this->converterNomeApp($request->app);
        if (is_dir(ROOT . '/views/pages/painel/' . $appReal . '/routes')) {
            throw new Excecao(status: 404);
        }

        $config = $this->config($appReal, 'visualizar');
        if (!$config->permissao->status) {
            mensagemStatus(403, localhost: 'Você não tem permissão para mudar o status.');
        } else if (!in_array($request->status, $config->visualizar->status)) {
            mensagemErro('Status inválido!', 'O valor do status não é um valor permitido.');
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api->body([
            'status' => $request->status
        ])->put($config->api->uri . '/' . $request->id);

        $dado = $this->validarRetornoApi($dado);
        if ($dado instanceof Response) {
            return $dado;
        }

        return mensagemErro($dado->erro->titulo ?? 'Erro!', $dado->erro->mensagem ?? 'Ocorreu um erro ao mudar seu status.');
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    public function add(Request $request, $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'add');

        if (!$config->permissao->add) {
            throw new Excecao(status: 404);
        }
        return view(
            arquivo: $config->add->app . '.add',
            var: [
                'app' => $app,
                'config' => $config,
                'acao' => 'add',
                'request' => $request,
                'appVoltar' => !empty($config->add->link) ? [$config->add->link, ''] : ''
            ],
            css: $config->add->css,
            js: $config->add->js,
        );
    }

    public function postSalvar(request $request, $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'salvar');

        $acao = $request->existe('id') && !empty($request->id) ? 'update' : 'insert';
        if (
            ($acao == 'update' && !$config->permissao->editar) ||
            ($acao == 'insert' && !$config->permissao->add)
        ) {
            throw new Excecao(status: 403);
        }

        $requestCampo = $config->salvar->salvar ?? [];
        if ($acao == 'insert' && inKey('salvar.insert', $config) && is_array($config->salvar->insert) && $config->salvar->insert) {
            $requestCampo = array_merge($requestCampo, $config->salvar->insert);
        } elseif ($acao == 'update' && inKey('salvar.update', $config) && is_array($config->salvar->update) && $config->salvar->update) {
            $requestCampo = array_merge($requestCampo, $config->salvar->update);
        }

        if (empty($requestCampo)) {
            throw new Erro(mensagem: 'Não existe uma lista de indices para salvar ou ela está vazia.');
        }
        $lista = $request->_POST(html: false);
        if (empty($lista)) {
            throw new Erro(mensagem: 'Não existe uma lista de indices para salvar ou ela está vazia.');
        }

        $lista = $this->criptografarListaDado($lista, $requestCampo, $config->api->criptografar);

        $uri = $config->api->uri;
        if ($acao == 'insert') {
            $Api = new ApiHelper(token: true);
            $dado = $Api->body($lista)->post($uri);
        } else {
            $Api = new ApiHelper(token: true);
            $dado = $Api->body($lista)->put($uri . '/' . $request->id);
        }

        $dado = $this->validarRetornoApi($dado);
        if ($dado instanceof Response) {
            return $dado;
        }

        $status = in_array($Api->status(), [201, 204]);
        if (!$status && object_key_exists('status', $dado) && $dado->status == 'erro') {
            return new Response(json: $dado, status: $Api->status());
        } else if (!$status) {
            mensagemErro('Erro ao salvar!', 'Ocorreu um erro ao salvar, por favor, tente novamente.', 500);
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => [
                'id' => $acao == 'insert' ? $dado->dado->id : $request->id
            ]
        ], status: $Api->status());
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */
    public function editar(Request $request, $app, $uuid)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'add');

        if (!$config->permissao->editar) {
            throw new Excecao(status: 404);
        }

        $dado = (new ApiHelper(token: true))->get($config->api->uri . '/' . $uuid);
        $dado = $this->validarRetornoApi($dado, true);
        if ($dado instanceof Response) {
            return $dado;
        }

        return view(
            arquivo: $config->add->app . '.add',
            var: [
                'app' => $app,
                'config' => $config,
                'acao' => 'editar',
                'dado' => $this->tratarListaDeRetorno($dado->dado, $config->api->criptografar),
                'request' => $request,
                'appVoltar' => !empty($config->add->link) ? [$config->add->link, ''] : '',
                'linkVoltar' => $config->add->link

            ],
            css: $config->add->css,
            js: $config->add->js,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    public function download(Request $request, string $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'download');

        if (!$config->permissao->index || !$config->permissao->download) {
            throw new Excecao(status: 403);
        }

        return view(
            arquivo: $config->download->app . '.download',
            var: [
                'app' => $app,
                'config' => $config,
                'pesquisa' => $request->pesquisa,
                'filtro' => $request->filtro,
                'ordem' => $request->ordem,
            ]
        );
    }
    public function postDownload(Request $request, string $app)
    {
        $request->vazio('senha', mensagem: 'Digite sua senha para fazer o download.');
        $request->vazio('campo', mensagem: 'Você tem que escolher pelo menos 1 item para continuar.');

        if ($request->termo != 'sim') {
            mensagemErro(
                'Campo obrigatório!',
                'Você tem que marcar o box para confirmar que você tem permissão para fazer o download.'
            );
        }

        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'download');

        if (!$config->permissao->index || !$config->permissao->download) {
            mensagemStatus(403);
        }

        $ApiSenha = new ApiHelper(token: true);
        $dadoSenha = $this->criptografarListaDado(['senha' => $request->senha], ['senha'], ['senha']);

        $ApiSenha
            ->body($dadoSenha)
            ->post('/usuario-equipe/validar-senha');

        $ApiSenha = $this->validarRetornoApi($ApiSenha);
        if ($ApiSenha instanceof Response) {
            return $ApiSenha;
        }

        foreach ($request->campo as $valor) {
            if (!in_array($valor, $config->download->campo)) {
                mensagemErro('Campo inválido!', 'Você não tem permissão para enviar um ou mais campos.', 403);
            }
        }
        $payload = [
            'campo' => $request->campo,
            'pesquisa' => !$request->vazio('pesquisa') ? base64Decode($request->pesquisa, 'pesquisa') : '',
            'ordem' => !$request->vazio('ordem') ? base64Decode($request->ordem, 'ordem') : '',
            'app' => $appReal,
            'usuario' => sessao('USUARIO.id')
        ];

        $filtro = !$request->vazio('filtro') ? base64Decode($request->filtro, 'filtro') : [];
        foreach ($filtro as $ind => $val) {
            $payload[$ind] = $val;
        }

        $payload = (new CryptHelper(chavePublica: $this->pegarChavePublica([1])))->encode($payload);

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao salvar o seu pedido, por favor, tente novamente.')
            ->body([
                'payload' => $payload,
                'tipo' => 'download.privado'
            ])
            ->post('/mensageria')
            ->object();

        return mensagemSucesso([
            'id' => $dado->dado->id
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTRAR
    |--------------------------------------------------------------------------
    */
    public function filtrar(Request $request, $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'filtrar');

        if (!$config->permissao->index || !$config->permissao->filtrar) {
            throw new Excecao(status: 403);
        }

        return view(
            arquivo: $config->filtrar->app . '.filtrar',
            var: [
                'app' => $app,
                'config' => $config,
                'ordem' => $request->ordem
            ]
        );
    }

    public function postFiltrar(Request $request, $app)
    {
        $ordem = $request->existe('ordem') && !empty($request->ordem) ? '&ordem=' . $request->ordem : '';
        if ($request->existe('pesquisa') && !empty($request->pesquisa)) {
            return new Response(url: LINK . '/app/' . $app . '?pesquisa=' . base64Encode($request->pesquisa, 'pesquisa') . $ordem);
        }
        $dado = $request->exeto(['pagina', 'pesquisa', 'ordem'], false);
        $lista = [];
        foreach ($dado as $ind => $val) {
            if (empty($val)) {
                continue;
            }
            $lista[$ind] = validarData($val) ? dataBanco($val) : $val;
        }
        return new Response(url: LINK . '/app/' . $app . '?filtro=' . base64Encode($lista, 'filtro') . $ordem);
    }

    private function pegarFiltro(Request $request, $config)
    {
        if ($request->existe('pesquisa') && !empty($request->pesquisa)) {
            return ['pesquisa' => ['Pesquisa', base64Decode($request->pesquisa, 'pesquisa')]];
        }
        if (!$request->existe('filtro') && !empty($request->filtro)) {
            return [];
        }
        $filtro = base64Decode($request->filtro, 'filtro');
        if (!is_array($filtro) || !$filtro) {
            return [];
        }
        $lista = [];
        $nome = $config->filtrar->nome;
        $valor = $config->filtrar->valor;
        foreach ($filtro as $ind => $val) {
            if (is_array($val)) {
                $val = count($val) . ' item(s)';
            } elseif (isset($valor[$ind][$val])) {
                $val = $valor[$ind][$val];
            }
            $lista[$ind] = [$nome[$ind] ?? str_replace('_', ' ', $ind), $val];
        }
        return $lista;
    }

    public function removerFiltro(Request $request, $app)
    {
        $indice = $request->indice;
        $ordem = $request->existe('ordem') && !empty($request->ordem) && $indice != 'ordem' ? 'ordem=' . $request->ordem : '';
        $filtro = $request->existe('filtro') && !empty($request->filtro) ? base64Decode($request->filtro, 'filtro') : '';

        $validar = is_array($filtro) && $filtro;
        if ($validar && $indice != 'ordem' && array_key_exists($indice, $filtro)) {
            unset($filtro[$indice]);
        }

        $url = LINK . '/app/' . $app;
        if ($indice == 'ordem' && $validar) {
            $url = LINK . '/app/' . $app . '?filtro=' . $request->filtro;
        } elseif (empty($filtro) && !empty($ordem)) {
            $url = LINK . '/app/' . $app . '?' . $ordem;
        } elseif (!empty($filtro)) {
            $ordem = !empty($ordem) ? '&' . $ordem : '';
            $url = LINK . '/app/' . $app . '?filtro=' . base64Encode($filtro, 'filtro') . $ordem;
        }
        return new Response(url: $url);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    public function postDeletar(Request $request, $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'deletar');
        if (!$config->permissao->deletar) {
            throw new Excecao(status: 403);
        }

        foreach ($request->id as $id) {
            $Api = new ApiHelper(token: true);
            $Api->delete($config->api->uri . '/' . $id);
            if ($Api->status() == 401) {
                return new Response(json: ['status' => 'deslogado'], status: 401);
            } else if ($Api->status() == 204) {
                continue;
            }

            mensagemErro(
                'Erro ao deletar!',
                'Ocorreu um erro ao deletar um registro, por favor, recarregue a página e tente novamente',
                $Api->status()
            );
        }

        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDENAR
    |--------------------------------------------------------------------------
    */
    public function postOrdem(Request $request, $app)
    {
        $appReal = $this->converterNomeApp($app);
        $config = $this->config($appReal, 'ordem');
        if (!$config->permissao) {
            throw new Excecao(status: 403);
        }
        $id = $request->chave('id', []);
        // $Model = $this->getModel(app: $appReal, model: $config->model);
        // $Model->ordenarLista($id, $request->chave('pagina', 1));
        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECIONAR
    |--------------------------------------------------------------------------
    */
    public function redirecionar(Request $request)
    {
        $url = $request->url;
        if (str_starts_with($url, LINK)) {
            return new Response(url: $url);
        }
        return view('painel.default.redirecionar', var: [
            'link' => $url
        ]);
    }
}
