<?php

namespace App\Models\Api\AlbumFoto;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Pagina;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class FotoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_ALBUM_FOTO;
    public string $album;
    public Pagina $pagina;
    public int $id_album_dado;
    public Status $status;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'imagem', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('ordem', 'ASC')
            ->read();

        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'imagem' => arquivoPublico('album_foto', $r->imagem),
                'status' => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $Where = new Where($this);
        if ($this->pExiste('album') && !empty($this->album)) {
            $this->id_album_dado = (new OrmHelper(TABELA_ALBUM_DADO))->pegarIdPeloUuid($this->album);
            $Where->linha('id_album_dado');
        }
        $Where->linha('status');
        return $Where;
    }
}
