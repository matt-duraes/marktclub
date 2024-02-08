<?php

namespace App\Models\Api\PublicacaoYoutube;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoYoutube\Local;
use App\Classes\PublicacaoYoutube\Ordem;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class YoutubeModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_YOUTUBE;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public string $pesquisa = '';
    public Ordem $ordem;
    public Botao $publicado;
    public Status $status;
    public Local $local;
    public Botao $restrita;
    public Botao $site;

    public function __construct()
    {
        parent::__construct();
        $this->quantidade = new Quantidade(null);
        $this->ordem = new Ordem();
        $this->publicado = new Botao(null);
        $this->restrita = new Botao(null);
        $this->site = new Botao(null);
        $this->status = new Status(null);
        $this->local = new Local(null);
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'url', 'video', 'data_inicio', 'data_final', 'status'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
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
                new Data($r->data_inicio),
                new Data($r->data_final),
                $statusIndice == Status::ATIVO
            );
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'texto'       => $r->texto,
                'url'         => $r->url,
                'video'       => $r->video,
                'data_inicio' => $r->data_inicio,
                'publicado'   => $publicado->indice(),
                'status'      => $statusIndice
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;
        $publicado = $this->publicado->valido();

        if ($this->status->valido() && !$publicado) {
            $where[] = ['status', $this->status->numero()];
        }
        if ($this->local->valido()) {
            $where[] = ['local', $this->local->numero()];
        }

        if ($publicado && $this->publicado->valor() == Botao::SIM) {
            $where[] = [
                [
                    'OR',
                    ['data_inicio', 'null'],
                    ['data_inicio', ''],
                    ['data_inicio', '<=', hoje() . ' 23:59:59'],
                ],
                [
                    'OR',
                    ['data_final', 'null'],
                    ['data_final', ''],
                    ['data_final', '>=', hoje()],
                ],
                ['status', (new Status(Status::ATIVO))->numero()]
            ];
        } elseif ($publicado && $this->publicado->valor() == Botao::NAO) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', (new Status(Status::ATIVO))->numero()]
            ];
        }
        if ($this->restrita->valido()) {
            $where[] = ['permissao_restrita', $this->restrita->numero()];
        }
        if ($this->site->valido()) {
            $where[] = ['permissao_site', $this->site->numero()];
        }
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'like', '%' . $this->pesquisa . '%'];
        }
        return $where;
    }
}
