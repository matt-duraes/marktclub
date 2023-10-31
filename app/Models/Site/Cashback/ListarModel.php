<?php

namespace App\Models\Site\Cashback;

use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use Order\OrderInterface;
use App\Classes\Geral\Status;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;
use App\Classes\ParceiroCashback\Ordem;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade,
        private ?string $pesquisa = null,
        private ?string $categoria = null,
        private ?Ordem $ordem = null
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->json($this->pegarWhere())
            ->get('/parceiro-cashback')
            ->object();

        return (object)[
            'tipo'      => 'cashback',
            'lista'     => $this->montarLista($dado->dado->lista ?? []),
            'paginacao' => $dado->dado->pagina ?? [],
        ];
    }

    private function pegarWhere()
    {
        $pagina = $this->pagina;
        $quantidade = $this->quantidade;
        $where = [
            'status'     => Status::ATIVO,
            'pagina'     => $pagina->numeroPadrao(),
            'quantidade' => $quantidade->numeroPadrao()
        ];
        $ordem = $this->ordem;
        if ($ordem instanceof OrderInterface && $ordem->valido()) {
            $where['ordem'] = $ordem->valor();
        }
        if (!empty($this->categoria)) {
            $where['categoria'] = $this->categoria;
        }
        if (!empty($this->pesquisa)) {
            $where['pesquisa'] = $this->pesquisa;
        }
        return $where;
    }

    private function montarLista($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] =
                (object)[
                    'id'       => $r->id,
                    'titulo'   => $r->titulo,
                    'link'     => route('cashback.detalhe') . '/' . $r->url,
                    'imagem'   => $r->imagem,
                    'desconto' => $r->comissao_minima,
                    'tipo'     => 'cashback'
                ];
        }
        return $retorno;
    }

}
