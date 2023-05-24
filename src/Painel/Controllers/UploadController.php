<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use PainelModel\Upload\Helper;

final class UploadController extends Controller
{
    private ApiHelper $Api;
    public function __construct()
    {
        $this->Api = new ApiHelper(token: true);
        parent::__construct();
    }
    /*
    |--------------------------------------------------------------------------
    | RETORNA A BUSCA DE IMAGENS
    |--------------------------------------------------------------------------
    */
    public function postBuscar(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        $arquivo = $this
            ->Api
            ->validar('Erro ao buscar lista de arquivos')
            ->json([
                'pagina' => $request->pagina,
                'pesquisa' => $request->pesquisa,
                'grupo' => $request->grupo_atual
            ])
            ->get('/upload-arquivo')->object();

        $header = $this
            ->Api
            ->validar('Erro ao buscar lista de headers')
            ->get('/upload-grupo/pai/' . $request->grupo_atual)->object();
        $diretorio = $this
            ->Api
            ->validar('Erro ao buscar lista de diretório')
            ->get('/upload-grupo/filho/' . $request->grupo_atual)->object();

        return mensagemSucesso([
            'header' => $header->dado ?? [],
            'diretorio' => $diretorio->dado[0]->lista ?? [],
            'arquivo' => $arquivo->dado->lista,
            'pagina' => $arquivo->dado->pagina->total
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EXTENSÃO
    |--------------------------------------------------------------------------
    */
    public function postExtensao(Request $request)
    {
        $grupo = $this
            ->Api
            ->validar('Ocorreu um erro ao buscar grupo', status: 404)
            ->get('/upload-grupo/' . $request->grupo)
            ->object();

        return mensagemSucesso(['extensao' => $grupo->dado->extensao]);
    }

    /*
    |--------------------------------------------------------------------------
    | DIRETORIO
    |--------------------------------------------------------------------------
    */
    public function postEstruturaDiretorio(Request $request)
    {
        $grupo = $this
            ->Api
            ->validar('Ocorre um erro ao buscar a estrutura de diretórios', status: 404)
            ->get('/upload-grupo/filho/' . $request->grupo)
            ->object();

        return mensagemSucesso([
            'diretorio' => $grupo->dado
        ]);
    }
    public function postMover(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        if (!empty($request->grupo_destino) && $request->grupo_destino != $request->grupo_atual) {
            $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_destino);
        }

        $Mover = new Helper($request);
        $Mover->moverArquivo();

        return mensagemSucesso($Mover->retornoMover, 201);
    }

    public function postCriarDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $grupo = (new Helper())->criarDiretorio($request->grupo_atual, $request->nome);

        return mensagemSucesso([
            'id' => $grupo->dado->id,
            'nome' => $grupo->dado->nome,
        ], status: 201);
    }
    public function postRenomearDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $this
            ->Api
            ->validar('Erro ao renomear o diretório.')
            ->body([
                'nome' => $request->nome
            ])->put('/upload-grupo/' . $request->grupo_atual)
            ->object();

        return new Response(status: 204);
    }

    public function postDeletarDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $this
            ->Api
            ->validar('Erro ao deletar diretório.')
            ->delete('/upload-grupo/' . $request->grupo_atual);

        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postSalvar(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $arquivo = $this
            ->Api
            ->validar('Erro ao fazer upload da imagem')
            ->body(['grupo' => $request->grupo_atual])
            ->arquivo(['arquivo' => $request->getFiles('arquivo')])
            ->post('/upload-arquivo')->object()->dado;

        return mensagemSucesso([
            'id' => $arquivo->id,
            'equipe' => $arquivo->equipe,
            'nome' => $arquivo->nome,
            'extensao' => $arquivo->extensao,
            'tamanho' => $arquivo->tamanho,
            'largura' => $arquivo->largura,
            'altura' => $arquivo->altura,
            'arquivo' => $arquivo->link,
            'data' => dataBr($arquivo->data_criacao, 'd/m/Y H:i')
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | RENOMEAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postRenomear(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        $request->vazio('nome', mensagem: 'Digite um nome para o arquivo.');

        $this
            ->Api
            ->validar('Erro ao renomear arquivo.')
            ->body([
                'nome' => $request->nome
            ])->put('/upload-arquivo/' . $request->id);

        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postEditar(Request $request)
    {
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postDeletar(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        foreach ($request->id as $id) {
            $this
                ->Api
                ->validar('Ocorre um erro ao deletar um ou mais arquivos.')
                ->delete('/upload-arquivo/' . $id);
        }
        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PRIVADO
    |--------------------------------------------------------------------------
    */
    private function validarGrupoAtual($grupoInicial, $grupoAtual): void
    {
        if ($grupoAtual == $grupoInicial) {
            return;
        }

        $valido = $this->Api->json([
            'raiz' => $grupoInicial,
            'grupo' => $grupoAtual
        ])->get('/upload-grupo/validar')->object()->dado->valido ?? 'nao';

        if ('sim' !== $valido) {
            mensagemErro('Erro!', 'Não foi possível validar o grupo.');
        }
    }
}
