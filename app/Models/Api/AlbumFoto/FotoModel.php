<?php

namespace App\Models\Api\AlbumFoto;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Pagina;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class FotoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_ALBUM_DADO;
    public string $algum;
    public Pagina $pagina;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'imagem', 'status'])
            ->where($this->pegarWhere())
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
        $Where->linha('album', campo: 'id_album_dado');
        return $Where;
    }
}
