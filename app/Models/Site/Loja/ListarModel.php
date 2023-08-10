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
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Estabelecimento;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    private array $mapa = [];

    public function __construct(
        private Inteiro $pagina = new Inteiro(1),
        private Inteiro $quantidade = new Inteiro(50),
        private Botao $favorito = new Botao(Botao::NAO),
        private Tipo $tipo = new Tipo(),
        private Ordem $ordem = new Ordem(),
        private Categoria $categoria = new Categoria(null),
        private ?string $subcategoria = null,
        private Estabelecimento $estabelecimento = new Estabelecimento(null),
        private ?string $pesquisa = null,
        private ?float $latitude = null,
        private ?float $longitude = null,
        private Botao $acessado = new Botao(Botao::NAO)
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
            ->validar(login: true)
            ->json($this->pegarWhere())
            ->get('/parceiro-loja')
            ->object();
        return (object)[
            'tipo'      => $this->tipo->indice(),
            'lista'     => $this->montarLista($dado->dado->lista ?? []),
            'mapa'      => $this->mapa,
            'paginacao' => $dado->dado->pagina ?? [],
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
            $link = $link . '/' . $r->url;

            $dado = [
                'id'       => $r->id,
                'titulo'   => $r->titulo,
                'link'     => $link,
                'imagem'   => $r->imagem,
                'desconto' => $r->desconto,
                'favorito' => $r->favorito,
                'tipo'     => $r->tipo,
            ];
            foreach ($r->geolocalizacao ?? [] as $mapa) {
                $this->mapa[] = (object)array_merge($dado, [
                    'latitude'  => $mapa->latitude,
                    'longitude' => $mapa->longitude
                ]);
            }
            $retorno[] = (object)$dado;
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
        if ($this->categoria->valido()) {
            $where['categoria'] = $this->categoria->indice();
        }
        if (!empty($this->subcategoria)) {
            $where['subcategoria'] = $this->subcategoria;
        }
        if ($this->estabelecimento->valido()) {
            $where['estabelecimento'] = $this->estabelecimento->indice();
        }
        if (!empty($this->pesquisa)) {
            $where['pesquisa'] = $this->pesquisa;
        }
        if (!empty($this->latitude)) {
            $where['latitude'] = $this->latitude;
        }
        if (!empty($this->longitude)) {
            $where['longitude'] = $this->longitude;
        }
        if ($this->acessado->valido() && $this->acessado->valor() == Botao::SIM) {
            $where['mais_acessado'] = 'sim';
        }
        return $where;
    }
}
