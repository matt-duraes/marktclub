<?php

namespace App\Models\Api\ParceiroCupom;

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
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->campo(['uuid', 'descricao', 'cupom', 'desconto', 'categoria', 'link', 'validade', 'status', 'id_parceiro_loja'])
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo'], 'parceiro')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
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
                'status'    => (new Auditado($item->status))->indice(),
            ];
        }
        return $retorno;
    }
}
