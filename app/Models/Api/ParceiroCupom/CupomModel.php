<?php

namespace App\Models\Api\ParceiroCupom;

use App\Classes\Geral\Status;
use App\Classes\ParceiroCupom\Auditado;
use App\Classes\ParceiroLoja\Categoria;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CupomModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_PARCEIRO_CUPOM;

    public function __construct(
        private ?string $pesquisa,
        private ?Categoria $categoria,
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null)
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->campo(['uuid', 'descricao', 'cupom', 'desconto', 'categoria', 'link', 'validade', 'auditado', 'status', 'id_parceiro_loja'])
            ->where($this->pegarWhere())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo'], 'parceiro')
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
                ['descricao', 'like', "%{$this->pesquisa}%"]
            ];
        }
        if ($this->categoria->valido()) {
            $where[] = ['categoria', $this->categoria->numero()];
        }
        return $where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $item) {
            $retorno[] = [
                'id'        => $item->uuid,
                'parceiro'  => $item->parceiro_titulo,
                'descricao' => $item->descricao,
                'cupom'     => $item->cupom,
                'desconto'  => $item->desconto,
                'categoria' => (new Categoria($item->categoria))->indice(),
                'link'      => $item->link,
                'validade'  => $item->validade,
                'status'    => (new Status($item->status))->indice(),
                'auditado'  => (new Auditado($item->auditado))->indice(),
            ];
        }
        return $retorno;
    }
}
