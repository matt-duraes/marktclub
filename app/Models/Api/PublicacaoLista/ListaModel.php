<?php

namespace App\Models\Api\PublicacaoLista;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Pagina;
use Modules\DataHora;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoLista\Grupo;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ListaModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_LISTA;
    public string $pesquisa;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public Grupo $grupo;
    public Status $status;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'texto_pequeno', 'texto_grande', 'url', 'lista',
                'grupo', 'imagem', 'data_criacao', 'data_atualizacao', 'status'
            ])
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
        $Grupo = new Grupo();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'               => $r->uuid,
                'titulo'           => $r->titulo,
                'texto'            => $r->texto_pequeno,
                'lista'            => jsonDecode($r->lista, true, true),
                'grupo'            => $Grupo->indice($r->grupo),
                'imagem'           => arquivoPrivado($r->imagem),
                'data_criacao'     => (new DataHora($r->data_criacao))->date(),
                'data_atualizacao' => (new DataHora($r->data_atualizacao))->date(),
                'url'              => !empty($r->texto_grande) ? $r->url : '',
                'status'           => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where
            ->linha('pesquisa', 'like%%')
            ->linha('grupo')
            ->linha('status');
        return $Where;
    }
}
