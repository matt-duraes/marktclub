<?php

namespace App\Models\Api\ParceiroCupom;

use App\Classes\ParceiroCupom\Ordem;
use App\Classes\ParceiroCupom\Status;
use App\Classes\ParceiroCupom\Tipo;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CupomModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_CUPOM;

    public function __construct(
        private ?string $pesquisa,
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private Ordem $ordem = new Ordem(),
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->campo(['uuid', 'titulo', 'tipo', 'texto', 'data_validade', 'cupom', 'link', 'imagem', 'status'])
            ->order($this->pegarOrdem())
            ->where($this->pegarWhere(), false)
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere()
    {
        $where = [];
        if ($this->pesquisa) {
            $where[] = [
                'OR',
                ['titulo', 'like', "%{$this->pesquisa}%"]
            ];
        }
        return $where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];

        $status = new Status();
        $tipo = new Tipo();

        foreach ($dado as $item) {
            $retorno[] = [
                'id'            => $item->uuid,
                'titulo'        => $item->titulo,
                'tipo'          => $tipo->indice($item->tipo),
                'texto'         => $item->texto,
                'data_validade' => $item->data_validade,
                'cupom'         => $item->cupom,
                'link'          => $item->link,
                'imagem'        => $item->imagem,
                'status'        => $status->indice($item->status)
            ];
        }
        return $retorno;
    }
}
