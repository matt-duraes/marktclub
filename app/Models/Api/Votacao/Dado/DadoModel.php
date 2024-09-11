<?php

namespace App\Models\Api\Votacao\Dado;

use App\Classes\Geral\Publicado;
use App\Classes\Votacao\Dado\Status;
use App\Classes\Votacao\Dado\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Botao;
use Modules\DataHora;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use Where\Where;

final class DadoModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    public Tipo $tipo;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public Botao $publicado;
    protected string $ormTabela = TABELA_VOTACAO_DADO;

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

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where
            ->linha('tipo')
            ->publicado();
        return $Where;
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
                'status'      => date('Y-m-d H:i:s') > (new DataHora($r->data_final))->date()
                    ? Status::INATIVO
                    : $statusIndice
            ];
        }
        return $retorno;
    }
}
