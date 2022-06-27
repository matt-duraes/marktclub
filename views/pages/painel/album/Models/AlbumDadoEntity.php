<?php

namespace Painel\Album\Models;

use stdClass;
use Http\Request;
use Modules\Data;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class AlbumDadoEntity extends AppGeralEntity
{
    protected string $_tabela = TABELA_ALBUM_DADO;
    protected array $_buscar = [
        'tipo', 'titulo', 'texto', 'width', 'height', 'extensao', 'data_publicacao', 'data_remocao', 'status', 'imagem'
    ];
    protected array $_salvar = [
        'tipo', 'titulo', 'texto', 'width', 'height', 'extensao', 'data_publicacao', 'data_remocao', 'status'
    ];
    protected array $_update = ['imagem'];

    protected Data $data_publicacao;
    protected Data $data_remocao;

    private array $imagemDaGaleria;

    /*
    |--------------------------------------------------------------------------
    | REGRAS DE NEGÓCIO
    |--------------------------------------------------------------------------
    */
    protected function regraSalvar()
    {
        $dataPublicacao = $this->data_publicacao->date();
        $dataRemocao = $this->data_remocao->date();
        $extensao = $this->extensao;

        if (empty($this->titulo)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar o título do álbum.');
        } else if (empty($dataPublicacao)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar a data de publicação do álbum.');
        } else if (!empty($dataRemocao) && $dataPublicacao > $dataRemocao) {
            mensagemErro('Campo inválido!', 'A data de remoção não pode ser menor que a data de publicação.');
        } else if (empty($this->tipo)) {
            mensagemErro('Campo obrigatório!', 'Você deve escolher o tipo do álbum.');
        } else if (!in_array($this->tipo, [1, 2, 3, 4])) {
            mensagemErro('Campo inválido!', 'Você deve passar um valor válido para o tipo do álbum.');
        } else if (in_array($this->tipo, [2, 3]) && empty($this->width)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar a largura da imagem.');
        } else if (in_array($this->tipo, [2, 4]) && empty($this->height)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar a altura da imagem.');
        } else if (empty($extensao)) {
            mensagemErro('Campo obrigatório!', 'Você deve escolher pelo menos uma extensão para as imagens.');
        }
        foreach ($extensao as $valor) {
            if (!in_array($valor, ['jpg', 'png', 'gif', 'svg'])) {
                mensagemErro('Campo obrigatório!', 'Uma ou mais extensões são inválidas.');
            }
        }
    }

    protected function regraPosBuscar()
    {
        $this->extensao = jsonDecode($this->extensao, true);
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA PARA DESTRUIR ÁLBUM
    |--------------------------------------------------------------------------
    */
    protected function regraDestruir()
    {
        $Arquivo = new AlbumArquivoModel();
        $lista = $Arquivo->listarTodasAsImagens($this->prop('id'));
        if (!$lista) {
            $this->imagemDaGaleria = [];
            return;
        }
        foreach ($lista as $imagem) {
            $this->imagemDaGaleria[] = $imagem->imagem;
        }
    }
    protected function regraPosDestruir()
    {
        if (!$this->imagemDaGaleria) {
            return;
        }
        foreach ($this->imagemDaGaleria as $imagem) {
            $arquivo = DIRETORIO_PRIVADO . '/album/' . $imagem;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GETS DO SISTEMA
    |--------------------------------------------------------------------------
    */
    protected function getId()
    {
        return $this->prop('id');
    }

    /*
    |--------------------------------------------------------------------------
    | DADOS PARA EDITAR
    |--------------------------------------------------------------------------
    */
    public function dadoEditar(): stdClass
    {
        return (object)[
            'id' => $this->id,
            'tipo' => $this->tipo,
            'titulo' => $this->titulo,
            'texto' => $this->texto,
            'width' => $this->width,
            'height' => $this->height,
            'data_publicacao' => $this->data_publicacao->data(),
            'data_remocao' => $this->data_remocao->data(),
            'extensao' => $this->extensao,
            'status' => $this->status
        ];
    }

    public function setarManual(Request $request)
    {
        $this->tipo = $request->tipo;
        $this->titulo = $request->titulo;
        $this->texto = $request->_POST('texto', true, false);
        $this->width = $request->width;
        $this->height = $request->height;
        $this->extensao = $request->extensao;
        $this->data_publicacao = new Data($request->data_publicacao, obrigatorio: false, erro: false);
        $this->data_remocao = new Data($request->data_remocao, obrigatorio: false, erro: false);
        $this->status = $request->status;
    }
}
