<?php

namespace PainelController;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;

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
        if (empty($request->grupo_destino)) {
            mensagemErro('Erro!', 'Você deve escolher um diretório para mover ou criar uma nova pasta.');
        } else if ($request->grupo_destino == $request->grupo_atual && empty($request->nome)) {
            mensagemErro(
                'Erro!',
                '
                    Você não pode mover os arquivos para o mesmo diretório que eles estão no momento,
                    escolha um novo diretório ou crie uma nova pasta no diretório escolhido.
                '
            );
        }

        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        if (!empty($request->grupo_destino) && $request->grupo_destino != $request->grupo_atual) {
            $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_destino);
        }

        if (!empty($request->nome)) {
            $GrupoDestino = new GrupoEntity(
                grupo: $request->grupo_destino,
                nome: $request->nome
            );
            $GrupoDestino->salvar();
            $retorno = [
                'id' => $GrupoDestino->id,
                'nome' => $GrupoDestino->nome
            ];
        } else {
            $retorno = [];
            $GrupoDestino = new GrupoEntity();
            $GrupoDestino->id($request->grupo_destino);
        }

        $Arquivo = new ArquivoModel();
        $Arquivo->moverArquivos($request->id, $GrupoDestino);

        return new Response(json: $retorno, status: 201);
    }

    public function postCriarDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $grupo = $this
            ->Api
            ->validar('Erro ao criar diretório')
            ->body([
                'grupo' => $request->grupo_atual,
                'nome' => $request->nome
            ])
            ->post('/upload-grupo')
            ->object();

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
    | SALCAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postSalvar(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $this
            ->Api
            ->body(['grupo' => $request->grupo_atual])
            ->arquivo(['arquivo' => $request->arquivo])
            ->post('/upload-arquivo')->object();
        // $Arquivo = new ArquivoEntity(
        //     arquivo: $request->arquivo,
        //     grupo: $request->grupo_atual
        // );
        // $Arquivo->salvar();

        // return new Response(json: [
        //     'id' => $Arquivo->id,
        //     'equipe' => sessao('USUARIO.nome'),
        //     'nome' => $Arquivo->nome,
        //     'extensao' => $Arquivo->extensao,
        //     'tamanho' => $Arquivo->tamanho,
        //     'largura' => $Arquivo->largura,
        //     'altura' => $Arquivo->altura,
        //     'arquivo' => arquivoPrivado($Arquivo->id),
        //     'data' => dataBr($Arquivo->data_criacao, 'd/m/Y H:i')
        // ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | RENOMEAR IMAGEM
    |--------------------------------------------------------------------------
    */
    public function postRenomear(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo_atual);
        $idGrupo = $Grupo->get('id');

        $Arquivo = new ArquivoEntity();
        $Arquivo->buscar([
            ['uuid', $request->id],
            ['id_upload_grupo', $idGrupo]
        ]);
        $Arquivo->nome = $request->nome;
        $Arquivo->salvar();

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

        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo_atual);
        $idGrupo = $Grupo->get('id');

        foreach ($request->id as $arquivo) {
            $Arquivo = new ArquivoEntity();
            $Arquivo->buscar([
                ['uuid', $arquivo],
                ['id_upload_grupo', $idGrupo]
            ]);
            $Arquivo->destruir();
        }

        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PRIVADO
    |--------------------------------------------------------------------------
    */
    private function validarGrupoAtual($grupoInicial, $grupoAtual)
    {
        $valido = $this->Api->json([
            'raiz' => $grupoInicial,
            'grupo' => $grupoAtual
        ])->get('/upload-grupo/validar')->object()->dado->valido ?? 'nao';

        if ('sim' !== $valido) {
            mensagemErro('Erro!', 'Não foi possível validar o grupo.');
        }
    }
}
