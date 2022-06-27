<?php

namespace App\Controllers\Painel;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Painel\Upload\GrupoModel;
use App\Models\Painel\Upload\GrupoEntity;
use App\Models\Painel\Upload\ArquivoModel;
use App\Models\Painel\Upload\ArquivoEntity;

final class UploadController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | RETORNA A BUSCA DE IMAGENS
    |--------------------------------------------------------------------------
    */
    public function postBuscar(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $pagina = $request->pagina;
        $pesquisa = $request->pesquisa;

        $Arquivo = new ArquivoModel();
        $arquivo = $Arquivo->buscarArquivos($pagina, $pesquisa, $request->grupo_atual);

        $Grupo = new GrupoModel();

        return new Response(json: [
            'header' => $pagina == 1 ? $Grupo->pegarGrupoPai($request->grupo_atual) : [],
            'diretorio' => $pagina == 1 && empty($pesquisa) ? $Grupo->listarSubGrupo($request->grupo_atual) : [],
            'arquivo' => $arquivo->lista,
            'pagina' => $arquivo->pagina
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EXTENSÃO
    |--------------------------------------------------------------------------
    */
    public function postExtensao(Request $request)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo);

        return new Response(json: ['extensao' => '.' . implode(',.', $Grupo->get('extensao'))]);
    }

    /*
    |--------------------------------------------------------------------------
    | DIRETORIO
    |--------------------------------------------------------------------------
    */
    public function postEstruturaDiretorio(Request $request)
    {
        $Grupo = new GrupoModel();
        return new Response(json: [
            'diretorio' => $Grupo->listarTodaArvoreDiretorio($request->grupo)
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

        $Grupo = new GrupoEntity(
            grupo: $request->grupo_atual,
            nome: $request->nome
        );
        $Grupo->salvar();

        return new Response(json: [
            'id' => $Grupo->id,
            'nome' => $Grupo->nome,
        ], status: 201);
    }
    public function postRenomearDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);
        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo_atual);
        $Grupo->nome = $request->nome;
        $Grupo->salvar();

        return new Response(status: 204);
    }
    public function postDeletarDiretorio(Request $request)
    {
        $this->validarGrupoAtual($request->grupo_inicial, $request->grupo_atual);

        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo_atual);
        $Grupo->destruir();

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

        $Arquivo = new ArquivoEntity(
            arquivo: $request->arquivo,
            grupo: $request->grupo_atual
        );
        $Arquivo->salvar();

        return new Response(json: [
            'id' => $Arquivo->id,
            'equipe' => sessao('USUARIO.nome'),
            'nome' => $Arquivo->nome,
            'extensao' => $Arquivo->extensao,
            'tamanho' => $Arquivo->tamanho,
            'largura' => $Arquivo->largura,
            'altura' => $Arquivo->altura,
            'arquivo' => arquivoPrivado($Arquivo->id),
            'data' => dataBr($Arquivo->data_criacao, 'd/m/Y H:i')
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
        $Grupo = new GrupoModel();
        if (!$Grupo->validarGrupoAtual($grupoInicial, $grupoAtual)) {
            throw new Excecao('Erro!', 'Não foi possível validar o grupo.');
        }
    }
}
