<?php

namespace App\Models\Api\AlbumDado;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\DataHora;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class AlbumModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ALBUM_DADO;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'imagem', 'data_inicio', 'data_final', 'status'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        if (!chaveExiste('dado.lista', $dado)) {
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
            $statusIndice = $Status->indice($r->status);
            $publicado = new Publicado(
                inicio: new DataHora($r->data_inicio),
                final: new DataHora($r->data_final),
                ativo: $statusIndice === $Status::ATIVO
            );
            $retorno[] = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'texto'     => $r->texto,
                'imagem'    => arquivoPublico('album_foto', $r->imagem, padrao: ''),
                'publicado' => $publicado->indice(),
                'status'    => $statusIndice,
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;
        return $where;
    }
}
