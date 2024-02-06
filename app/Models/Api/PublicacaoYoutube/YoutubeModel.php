<?php

namespace App\Models\Api\PublicacaoYoutube;

use ORM\ORM;
use stdClass;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
final class YoutubeModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_YOUTUBE;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        if(!chaveExiste('dado.lista', $dado)){
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid
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

