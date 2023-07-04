<?php

namespace App\Models\Site\Loja;

use Http\Request;
use Helpers\ApiHelper;
use Helpers\ListaHelper;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Estabelecimento;

final class FiltroModel
{
    private array $dado = [
        'estado' => [
            'indice' => 'estado',
            'nome'   => 'Estado'
        ],
        'categoria' => [
            'indice' => 'categoria',
            'nome'   => 'Categoria'
        ],
        'subcategoria' => [
            'indice' => 'subcategoria',
            'nome'   => 'Subcategoria'
        ],
        'estabelecimento' => [
            'indice' => 'estabelecimento',
            'nome'   => 'Estabelecimento'
        ],
        'pesquisa' => [
            'indice' => 'pesquisa',
            'nome'   => 'Pesquisa'
        ],
        'ordem' => [
            'indice' => 'ordem',
            'nome'   => 'Ordem'
        ],
    ];
    public string $link;
    public array $uso = [];
    public ?string $estado = null;
    public ?string $categoria = null;
    public ?string $subcategoria = null;
    public ?string $estabelecimento = null;
    public ?string $pesquisa = null;
    public ?string $ordem = null;

    public function __construct(
        private Request $request
    ) {
        $this->link = route('loja.index');
        $this->montarDado();
    }

    private function montarDado()
    {
        $lista = limparVazioDeArray($this->request->dado());
        $permitido = array_keys($this->dado);
        $dado = $this->dado;

        $retorno = [];
        foreach ($lista as $ind => $val) {
            if (!in_array($ind, $permitido)) {
                mensagemStatus(404);
            } elseif (empty($val) || ($ind == 'subcategoria' && empty($this->request->categoria))) {
                continue;
            }
            $valorReal = $this->pegarValorReal($ind, $val);
            if (empty($valorReal)) {
                continue;
            }

            $retorno[] = $ind . '=' . $val;
            $uso = $dado[$ind];
            $uso['valor_real'] = $valorReal;
            $valor = str_replace(['"', "'", '\\', '/', '|'], '', $val);
            $uso['valor'] = $valor;
            $this->uso[] = $uso;
            $this->$ind = $valor;
        }
        $this->link .= '?' . implode('&', $retorno);
    }

    private function pegarValorReal($indice, $valor)
    {
        if ($indice == 'estado') {
            return (new ListaHelper())->estado()->r()[$valor] ?? $valor;
        } elseif ($indice == 'categoria') {
            return (new Categoria())->select()[$valor] ?? $valor;
        } elseif ($indice == 'estabelecimento') {
            return (new Estabelecimento())->select()[$valor] ?? $valor;
        } elseif ($indice == 'ordem') {
            return (new Ordem())->select()[$valor] ?? $valor;
        } elseif ($indice == 'subcategoria') {
            return $this->buscarSubcategoria($this->request->categoria, $valor);
        }
        return $valor;
    }

    private function buscarSubcategoria($categoria, $subcategoria)
    {
        return (new ApiHelper(scope: 'parceiro_subcategoria:listar'))
            ->json(['categoria' => $categoria])
            ->get('/parceiro-subcategoria/select')
            ->array()['dado'][$subcategoria] ?? '';
    }
}
