<?php

namespace App\Models\Api\Parceiro\Campanha;

use ORM\ORM;
use Where\Where;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Where\WhereInterface;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\Parceiro\Campanha\Ordem;
use App\Models\Api\Parceiro\Loja\LojaHelper;

final class CampanhaModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_CAMPANHA;
    public string $parceiro;
    public string $titulo;
    public string $pesquisa;
    public Data $data_inicio;
    public Data $data_final;
    public Status $status;
    public Botao $publicado;
    protected Ordem $ordem;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados()
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'data_inicio', 'data_final', 'imagem_desktop', 'imagem_mobile', 'link', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        if (existeErro($dado, 'lista') || empty($dado->lista)) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function montarDado(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'             => $r->uuid,
                'titulo'         => $r->titulo,
                'texto'          => $r->texto,
                'data_inicio'    => $r->data_inicio,
                'data_final'     => $r->data_final,
                'imagem_desktop' => arquivoPrivado($r->imagem_desktop),
                'imagem_mobile'  => arquivoPrivado($r->imagem_mobile),
                'link'           => $r->link,
                'publicado'      => (new Publicado(
                    new Data($r->data_inicio),
                    new Data($r->data_final),
                    ativo: $r->status == 1
                ))->indice(),
                'status'      => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): WhereInterface
    {
        $Where = new Where($this);
        $Where
            ->seVazio(propriedade: 'parceiro', vazio: false, callback: function () use ($Where) {
                $id = (new LojaHelper())->pegarIdPeloUuid($this->parceiro);
                if (empty($id)) {
                    return;
                }
                $Where->manual(['id_parceiro_loja', $id]);
            })
            ->linha('titulo', 'like%%')
            ->seVazio(propriedade: 'pesquisa', vazio: false, callback: function () use ($Where) {
                $pesquisa = '%' . $this->pesquisa . '%';
                $Where->manual([
                    'OR',
                    ['titulo', 'like', $pesquisa],
                    ['texto', 'like', $pesquisa]
                ]);
            })
            ->dataDeAte()
            ->linha('status')
            ->publicado();
        return $Where;
    }
}
