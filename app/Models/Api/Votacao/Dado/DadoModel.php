<?php

namespace App\Models\Api\Votacao\Dado;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Pagina;
use Modules\DataHora;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Classes\Votacao\Dado\Tipo;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DadoModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_VOTACAO_DADO;
    public Tipo $tipo;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'url', 'data_inicio', 'data_final', 'status'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
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
            $statusIndice = $Status->indice($r->status);
            $publicado = new Publicado(
                new DataHora($r->data_inicio),
                new DataHora($r->data_final),
                $Status::ATIVO == $statusIndice
            );
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'texto'       => $r->texto,
                'url'         => $r->url,
                'publicado'   => $publicado->indice(),
                'data_inicio' => $r->data_inicio,
                'data_final'  => $r->data_final,
                'status'      => $statusIndice,
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where->linha('tipo');
        return $Where;
    }
}
