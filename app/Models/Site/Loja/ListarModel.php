<?php

namespace App\Models\Site\Loja;

use stdClass;
use Modules\Botao;
use Modules\Inteiro;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Models\Site\ListarInterface;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    public function __construct(
        private Inteiro $pagina = new Inteiro(1),
        private Inteiro $quantidade = new Inteiro(20),
        private Botao $favorito = new Botao(Botao::NAO),
        private Tipo $tipo = new Tipo(),
        private Ordem $ordem = new Ordem(),
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->json($this->pegarWhere())
            ->get('/parceiro-loja')
            ->object();

        return (object)[
            'tipo'      => $this->tipo->indice(),
            'lista'     => $this->montarLista($dado->dado->lista),
            'paginacao' => $dado->dado->pagina,
        ];
    }

    private function montarLista(array $dado): array
    {
        $retorno = [];

        foreach ($dado as $r) {
            $link = route('loja.detalhe');
            if ($r->tipo == Tipo::FARMACIA) {
                $link = route('farmacia.detalhe');
            } elseif ($r->tipo == Tipo::AUTOMOVEL) {
                $link = route('automovel.modelo');
            }
            $retorno[] = (object)[
                'id'       => $r->id,
                'titulo'   => $r->titulo,
                'link'     => $link . '/' . $r->url,
                'imagem'   => $r->imagem,
                'desconto' => $r->desconto,
                'favorito' => $r->favorito,
                'tipo'     => $r->tipo,
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $pagina = $this->pagina;
        $quantidade = $this->quantidade;
        $where = [
            'status'     => Status::CONCLUIDO,
            'pagina'     => $pagina->valido() ? $pagina->numero() : 1,
            'quantidade' => $quantidade->valido() ? $quantidade->numero() : 20
        ];
        $favorito = $this->favorito;
        if ($favorito->valido() && $favorito->valor() == Botao::SIM) {
            $where['favorito'] = 'sim';
        }
        $ordem = $this->ordem;
        if ($ordem->valido()) {
            $where['ordem'] = $ordem->valor();
        }
        $tipo = $this->tipo;
        if ($tipo->valido()) {
            $where['tipo'] = $tipo->numero();
        }
        return $where;
    }
}
