<?php

namespace App\Models\Site\Loja;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Models\Site\ListarInterface;
use App\Classes\ParceiroLoja\TipoLoja;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    private array $mapa = [];
    private array $where = [];

    public function __construct(
        private TipoLoja $tipo = new TipoLoja(),
        private ?FiltroModel $Filtro = null,
        private ?string $id = null,
        private int $quantidade = 24
    ) {
        parent::__construct();
        if ($Filtro instanceof FiltroModel) {
            $this->where = $this->Filtro->pegarWhere();
        }
        $this->setarWhere();
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
            ->json($this->where)
            ->get('/parceiro-loja')
            ->object();

        $Retorno = new RetornoModel($dado->dado->lista ?? []);
        $this->mapa = $Retorno->mapa;

        $lista = $Retorno->retorno;
        $paginacao = $dado->dado->pagina ?? [];
        $registro = $dado->dado->registro ?? [];

        return (object)[
            'tipo'      => $this->tipo->indice(),
            'lista'     => $lista,
            'mapa'      => $this->mapa,
            'paginacao' => $paginacao,
            'registro'  => $registro
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONADO
    |--------------------------------------------------------------------------
    */
    public function listarRelacionado(): array
    {
        $dado = $this
            ->validar(login: true)
            ->get('/parceiro-loja/relacionado/' . $this->id)
            ->object();

        $Retorno = new RetornoModel($dado->dado ?? []);

        $this->mapa = $Retorno->mapa;
        return $Retorno->retorno;
    }

    private function setarWhere()
    {
        $where = [
            'status'     => Status::CONCLUIDO,
            'quantidade' => $this->quantidade
        ];
        if ($this->tipo->valido() && $this->tipo->indice() != 'loja') {
            $where['tipo_loja'] = $this->tipo->indice();
        } elseif ($this->tipo->indice() == 'loja') {
            $where['convenio'] = 'sim';
        }

        $where = array_merge($where, $this->where);
        $replace = [
            'acessado'        => 'mais_acessado',
            'estado'          => 'endereco_estado',
            'estabelecimento' => 'tipo_estabelecimento'
        ];
        if (!array_key_exists('pagina', $where)) {
            $where['pagina'] = 1;
        }
        foreach ($where as $ind => $val) {
            if (!array_key_exists($ind, $replace)) {
                continue;
            }
            $where[$replace[$ind]] = $val;
            unset($where[$ind]);
        }
        if (array_key_exists('endereco_estado', $where)) {
            $where['endereco_estado'] = [$where['endereco_estado']];
        }
        if (!array_key_exists('ordem', $where)) {
            $where['ordem'] = (new Ordem(Ordem::MAIS_NOVO))->valor();
        }
        $this->where = $where;
    }
}
