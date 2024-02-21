<?php

namespace App\Models\Api\PublicacaoArquivo;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoArquivo\Tipo;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoArquivo\Ordem;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ArquivoModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_ARQUIVO;
    protected Pagina $pagina;
    protected Quantidade $quantidade;
    protected Ordem $ordem;
    public ?string $pesquisa;
    public Data $data_inicio_de;
    public Data $data_inicio_ate;
    public Botao $publicado;
    public Tipo $tipo;
    public Status $status;
    public Botao $restrita;
    public Botao $site;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'imagem', 'arquivo', 'url', 'data_inicio', 'data_final', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
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
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'texto'     => $r->texto,
                'imagem'    => arquivoPrivado($r->imagem),
                'arquivo'   => arquivoPrivado($r->arquivo),
                'url'       => $r->url,
                'publicado' => $publicado->indice(),
                'status'    => $statusIndice
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where
            ->seValido(propriedade: 'publicado', valido: false, callback: function () use ($Where) {
                $Where
                    ->linha(propriedade: 'status')
                    ->data(data1: 'data_inicio_de', data2: 'data_inicio_ate');
            })
            ->linha('tipo')
            ->linha('restrita', campo: 'premissao_restrita')
            ->linha('site', campo: 'permissao_site')
            ->publicado()
            ->linha(propriedade: 'pesquisa', condicao: 'like%%', campo: 'titulo');
        return $Where;
    }
}
