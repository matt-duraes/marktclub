<?php

namespace Painel\Album\Models;

use ORM\ORM;

final class AlbumArquivoModel extends ORM
{

    protected string $_tabela = TABELA_ALBUM_ARQUIVO;

    public function listarImagens($id, int $pagina = 1)
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'imagem'])
            ->where(['id_album_dado', $id])
            ->order([
                ['ordem', 'ASC'],
                ['id', 'DESC'],
            ])
            ->pagina($pagina, 20)
            ->read();

        $dado->lista = $this->montarLista($dado->lista);
        return $dado;
    }

    private function montarLista($dado)
    {
        if (!$dado) {
            return [];
        }
        $lista = [];
        foreach ($dado as $r) {
            if (empty($r->imagem) || !file_exists(DIRETORIO_PUBLICO . '/album/' . $r->imagem)) {
                $this->deletarImagem($r->uuid);
                continue;
            }

            $lista[] = (object)[
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'imagem' => arquivoPublico('/album/', $r->imagem)
            ];
        }
        return $lista;
    }

    public function listarTodasAsImagens(int $album)
    {
        return $this->campo(['imagem'])->where(['id_album_dado', $album])->read();
    }

    private function deletarImagem(string $uuid): void
    {
        try {
            $this->where(['uuid', $uuid])->delete();
            return;
        } catch (\Throwable) {
            return;
        }
    }
}
