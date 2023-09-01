<?php

namespace App\Models\Api\ParceiroCupom;

use App\Classes\ParceiroLoja\Categoria;
use Modules\Botao;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use Http\Request;
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
    )
    {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'descricao', 'cupom', 'desconto', 'categoria', 'link', 'validade', 'auditoria'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $item) {
            $retorno[] = [
                'id' => $item->uuid,
                'descricao' => $item->descricao,
                'cupom' => $item->cupom,
                'desconto' => $item->desconto,
                'categoria' => (new Categoria($item->categoria))->indice(),
                'link' => $item->link,
                'validade' => $item->validade,
                'auditoria' => (new Botao($item->auditoria))->valor(),
            ];
        }
        return $retorno;
    }
}
