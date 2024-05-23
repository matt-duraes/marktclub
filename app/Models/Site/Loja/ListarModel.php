<?php

namespace App\Models\Site\Loja;

use stdClass;
use Helpers\ListaHelper;
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

        $lista = $this->montarLista($dado->dado->lista ?? []);
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

        return $this->montarLista($dado->dado ?? []);
    }

    private function montarLista(array $dado): array
    {
        $retorno = [];
        $dataNovo = dataRemover(hoje(), 1, 'mes');
        $listaEstado = (new ListaHelper())->estado()->r();
        $Favorito = new FavoritoModel();
        foreach ($dado as $r) {
            $link = route('loja.detalhe');
            if ($r->tipo_loja == TipoLoja::FARMACIA) {
                $link = route('farmacia.detalhe');
            } elseif ($r->tipo_loja == TipoLoja::AUTOMOVEL) {
                $link = route('automovel.modelo');
            } elseif ($r->tipo_loja == TipoLoja::CASHBACK) {
                $link = route('cashback.detalhe');
            } elseif ($r->tipo_loja == TipoLoja::PREMIUM) {
                $link = route('premium.detalhe');
            }
            $link = $link . '/' . $r->url;

            $estadoArray = jsonDecode($r->endereco_estado, true, true);
            $estadoNumero = count($estadoArray);
            $estado = '';
            if (in_array('GR', $estadoArray)) {
                $estado = 'Internacional';
            } elseif ($estadoNumero == 27) {
                $estado = 'Nacional';
            } elseif ($estadoNumero == 1 && array_key_exists($estadoArray[0], $listaEstado)) {
                $estado = $listaEstado[$estadoArray[0]];
            } elseif ($estadoNumero > 1) {
                $estado = $estadoNumero . ' estados';
            }
            $dado = [
                'id'       => $r->id,
                'titulo'   => $r->titulo,
                'link'     => $link,
                'imagem'   => $r->imagem_logo,
                'desconto' => $r->desconto,
                'favorito' => $Favorito->favorito($r->id),
                'novo'     => !empty($r->data_publicacao) && $r->data_publicacao > $dataNovo ? 'sim' : 'nao',
                'estado'   => $estado,
                'tipo'     => $r->tipo_loja,
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
