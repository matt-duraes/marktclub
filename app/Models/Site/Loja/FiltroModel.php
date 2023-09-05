<?php

namespace App\Models\Site\Loja;

use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Estabelecimento;

final class FiltroModel extends ClubeApiHelper
{
    private array $lista = [
        'loja' => [
            'lista'    => ['estado', 'cidade', 'categoria', 'subcategoria', 'estabelecimento', 'pesquisa', 'ordem'],
            'especial' => ['favorito', 'latitude', 'longitude', 'acessado', 'mapa', 'pagina', 'quantidade'],
            'filtro'   => [
                'estado'          => 'Estado',
                'cidade'          => 'Cidade',
                'categoria'       => 'Categoria',
                'subcategoria'    => 'Subcategoria',
                'estabelecimento' => 'Estabelecimento',
                'pesquisa'        => 'Pesquisa',
                'ordem'           => 'Ordem',
            ]
        ]
    ];
    private array $where = [];
    public bool $cache = false;
    public array $filtro = [];
    public bool $mapa = false;
    public bool $acessado = false;
    public bool $favorito = false;
    public string $estado = '';
    public string $cidade = '';
    public string $categoria = '';
    public string $subcategoria = '';
    public string $estabelecimento = '';
    public string $pesquisa = '';
    public string $ordem = '';
    public string $latitude = '';
    public string $longitude = '';
    public string $link = '';

    public function __construct(
        private array $dado = []
    ) {
        parent::__construct();
        $this->link = route('loja.index');
        $this->tratarRequest();
    }

    public function pegarWhere()
    {
        return $this->where;
    }

    private function tratarRequest()
    {
        $dado = $this->dado;
        $lista = array_merge($this->lista['loja']['lista'], $this->lista['loja']['especial']);

        $retorno = [];
        $link = [];

        foreach ($dado as $ind => $val) {
            if (
                (!in_array($ind, $lista) || empty($val)) ||
                (in_array($ind, ['acessado', 'favorito']) && $val != 'sim')
            ) {
                continue;
            }
            if (in_array($ind, ['latitude', 'longitude'])) {
                $this->mapa = true;
            } elseif ($ind == 'acessado') {
                $this->acessado = true;
            } elseif ($ind == 'favorito') {
                $this->favorito = true;
            }
            if (!in_array($ind, ['acessado', 'favorito', 'pagina', 'quantidade'])) {
                $this->$ind = $val;
            }
            $retorno[$ind] = $val;
            if (!in_array($ind, ['pagina', 'quantidade'])) {
                $link[] = $ind . '=' . $val;
            }
        }
        if (!empty($link)) {
            $this->link .= '?' . implode('&', $link);
        }

        $this->where = $retorno;
        $this->setarFiltro($retorno);
    }

    private function setarFiltro($lista)
    {
        $campo = $this->lista['loja']['filtro'];
        foreach ($lista as $ind => $val) {
            $valor = $val;
            if (!array_key_exists($ind, $campo)) {
                continue;
            } elseif ($ind == 'categoria') {
                $valor = (new Categoria($val))->nome();
            } elseif ($ind == 'estabelecimento') {
                $valor = (new Estabelecimento($val))->nome();
            } elseif ($ind == 'ordem') {
                $valor = (new Ordem($val))->nome();
            } elseif ($ind == 'subcategoria') {
                $valor = $this->buscarSubcategoria($val);
            }
            $this->filtro[] = (object)[
                'nome'   => $campo[$ind],
                'indice' => $ind,
                'valor'  => $valor
            ];
        }
    }

    private function buscarSubcategoria($subcategoria)
    {
        return $this
            ->get('/parceiro-subcategoria/select')
            ->array()['dado'][$subcategoria] ?? '';
    }
}
