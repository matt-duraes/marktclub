<?php

namespace Painel\DocumentoNoticia\Models;

use stdClass;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class DocumentoNoticiaEntity extends AppGeralEntity
{
    protected string $_tabela = TABELA_DOCUMENTO_NOTICIA;

    protected array $_buscar = ['titulo', 'texto', 'data_publicacao', 'fonte_nome', 'fonte_link', 'tag', 'status'];
    protected array $_insert = ['url'];
    protected array $_salvar = ['titulo', 'texto', 'data_publicacao', 'fonte_nome', 'fonte_link', 'tag', 'status'];
    protected string $_validarSalvar = '
        titulo|Titulo|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        data_publicacao|Data de publicação|vazio
        tag|Tag|obrigatorio|vazio|isArray
        fonte_link|Link da fonte|url
    ';

    protected function regraSalvar()
    {
        if (empty($this->fonte_nome) && !empty($this->fonte_link)) {
            throw new Excecao(
                titulo: 'Campo obrigatório!',
                mensagem: 'Você deve passar o nome da fonte do link informado.'
            );
        }
    }

    public Data $data_publicacao;

    public function dadoEditar(): stdClass
    {
        return (object)[
            'id' => $this->id,
            'titulo' => $this->titulo,
            'texto' => $this->texto,
            'fonte_nome' => $this->fonte_nome,
            'fonte_link' => $this->fonte_link,
            'data_publicacao' => $this->get('data_publicacao')->data(),
            'tag' => jsonDecode($this->tag, true),
            'status' => $this->status == 1 ? 1 : null,
        ];
    }

    public function setarManual(Request $request)
    {
        $this->titulo = $request->_POST('titulo');
        $this->tag = $request->_POST('tag');
        $this->fonte_nome = $request->_POST('fonte_nome');
        $this->fonte_link = $request->_POST('fonte_link');
        $this->texto = $this->limparDadoDoTexto($request->_POST('texto', false, false));
        $this->data_publicacao = new Data(data: $request->_POST('data_publicacao'), campo: 'Data de publicação');
        $this->status = $request->_POST('status') == 1 ? 1 : '';
    }
    private function limparDadoDoTexto($texto)
    {
        return str_replace('<p><br data-cke-filler="true"></p>', '', $texto);
    }
}
