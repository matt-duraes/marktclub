<?php

namespace Painel\Album\Models;

use ORM\ORM;

final class AlbumDadoModel extends ORM
{

    protected string $_tabela = TABELA_ALBUM_DADO;

    public function listarAlbuns()
    {
        $dado = $this
            ->campo(['id', 'uuid', 'titulo', 'imagem'])
            ->order('id', 'DESC')
            ->read();

        if (!$dado) {
            return [];
        }
        return $this->montarLista($dado);
    }

    private function montarLista($dado)
    {
        $lista = [];
        foreach ($dado as $r) {
            $lista[] = (object) [
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'imagem' => $this->pegarImagem($r->imagem, $r->id)
            ];
        }
        return $lista;
    }

    private function pegarImagem($imagem, $id)
    {
        if (!empty($imagem) && file_exists(DIRETORIO_PUBLICO . '/album/' . $imagem)) {
            return arquivoPublico('/album/', $imagem);
        }
        return $this->salvarPrimeiraImagem($id);
    }
    private function salvarPrimeiraImagem($id)
    {
        $Arquivo = new AlbumArquivoModel();
        $lista = $Arquivo->listarImagens($id);

        if (!$lista->lista) {
            return LINK_PADRAO . '/images/imagem_padrao.jpg';
        }

        $arquivo = arquivoNomeExt($lista->lista[0]->imagem);
        $this->where(['id', $id])->dado([
            'imagem' => $arquivo
        ])->update();

        return $lista->lista[0]->imagem;
    }
}
