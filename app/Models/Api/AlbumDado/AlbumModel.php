<?php

namespace App\Models\Api\AlbumDado;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\DataHora;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\AlbumDado\Ordem;
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
    protected Pagina $pagina;
    protected Quantidade $quantidade;
    protected Ordem $ordem;
    public Data $data_inicio_de;
    public Data $data_inicio_ate;
    public Botao $publicado;
    public Status $status;
    public Botao $restrita;
    public Botao $site;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'imagem', 'data_inicio', 'data_final', 'url', 'status'])
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
                inicio: new DataHora($r->data_inicio),
                final: new DataHora($r->data_final),
                ativo: $statusIndice === $Status::ATIVO
            );
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'texto'       => $r->texto,
                'imagem'      => arquivoPublico('album_foto', $r->imagem, padrao: ''),
                'data_inicio' => $r->data_inicio,
                'url'         => $r->url,
                'publicado'   => $publicado->indice(),
                'status'      => $statusIndice,
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
            ->linha('restrita', campo: 'permissao_restrita')
            ->linha('site', campo: 'permissao_site')
            ->publicado();
        return $Where;
    }
}
