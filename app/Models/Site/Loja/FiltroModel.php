<?php

namespace App\Models\Site\Loja;

use Helpers\ListaHelper;
use Helpers\LocalizacaoHelper;
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
    public bool $latitude_erro = false;
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
        $where = $this->where;
        if (array_key_exists('latitude', $where) || array_key_exists('longitude', $where)) {
            unset($where['estado']);
        }
        unset($where['cidade']);
        return $where;
    }

    private function tratarRequest()
    {
        $dado = $this->dado;
        $lista = array_merge($this->lista['loja']['lista'], $this->lista['loja']['especial']);

        $retorno = [];
        $link = [];

        if (array_key_exists('cidade', $dado) && !empty($dado['cidade'])) {
            unset($dado['latitude'], $dado['longitude']);
        }

        foreach ($dado as $ind => $val) {
            if (
                (!in_array($ind, $lista) || empty($val)) ||
                (in_array($ind, ['acessado', 'favorito']) && $val != 'sim')
            ) {
                continue;
            }
            if (in_array($ind, ['latitude', 'longitude', 'cidade'])) {
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
                $link[] = $ind . '=' . urlencode($val);
            }
        }
        if (!empty($link)) {
            $this->link .= '?' . implode('&', $link);
        }
        if (array_key_exists('cidade', $retorno)) {
            $retorno = $this->pegarGeolocalizacao($retorno);
        }
        $this->where = $retorno;
        $this->setarFiltro($retorno);
    }

    private function pegarGeolocalizacao($dado)
    {
        $linkInicio = str_contains($this->link, '?') ? '&' : '?';
        $Localizacao = new LocalizacaoHelper();
        try {
            $geolocalicacao = $Localizacao->pegarGeolocalizacaoPeloEndereco(pais: 'BR', estado: $dado['estado'], cidade: $dado['cidade']);
        } catch (\Throwable) {
            $this->link .= $linkInicio . 'latitude_erro=sim';
            return $dado;
        }

        $this->latitude = $geolocalicacao['latitude'];
        $this->longitude = $geolocalicacao['longitude'];
        $dado['latitude'] = $this->latitude;
        $dado['longitude'] = $this->longitude;

        $this->link .= $linkInicio . 'latitude=' . $this->latitude . '&longitude=' . $this->longitude;

        return $dado;
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
            } elseif ($ind == 'estado') {
                $valor = (new ListaHelper())->estado()->r()[$val] ?? $val;
            } elseif ($ind == 'subcategoria') {
                $valor = $this->buscarSubcategoria($val);
            }
            $this->filtro[] = (object)[
                'nome'       => $campo[$ind],
                'indice'     => $ind,
                'valor_real' => $val,
                'valor'      => $valor
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
