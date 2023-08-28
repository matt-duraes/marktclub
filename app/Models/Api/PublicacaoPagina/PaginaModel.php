<?php

namespace App\Models\Api\PublicacaoPagina;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoPagina\Ordem;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class PaginaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_PAGINA;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $pesquisa = null,
        private Ordem $ordem = new Ordem(null),
        private Status $status = new Status(null)
    ) {
        parent::__construct();
        $this->validarDado();
        $this->validarEmpresa();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'data_criacao', 'url', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montardado($dado->lista);
        return $dado;
    }

    private function montardado($lista)
    {
        if (!$lista) {
            return [];
        }

        $Status = new Status();
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'titulo'       => $r->titulo,
                'texto'        => $r->texto,
                'data_criacao' => $r->data_criacao,
                'url'          => $r->url,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function validarDado()
    {
        //
    }
}
