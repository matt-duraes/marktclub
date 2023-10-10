<?php

namespace App\Models\Site\Loja;

use stdClass;
use Helpers\ListaHelper;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Models\Site\ListarInterface;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    private array $mapa = [];
    private array $where = [];

    public function __construct(
        private Tipo $tipo = new Tipo(),
        private ?FiltroModel $Filtro = null,
        private ?string $id = null
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
        foreach ($dado as $r) {
            $link = route('loja.detalhe');
            if ($r->tipo == Tipo::FARMACIA) {
                $link = route('farmacia.detalhe');
            } elseif ($r->tipo == Tipo::AUTOMOVEL) {
                $link = route('automovel.modelo');
            }
            $link = $link . '/' . $r->url;

            $estadoArray = jsonDecode($r->estado, true, true);
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
                'imagem'   => $r->imagem,
                'desconto' => $r->desconto,
                'favorito' => $r->favorito,
                'novo'     => !empty($r->data_publicacao) && $r->data_publicacao > $dataNovo ? 'sim' : 'nao',
                'estado'   => $estado,
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

    private function setarWhere()
    {
        $where = [
            'status' => Status::CONCLUIDO,
            'tipo'   => $this->tipo->indice()
        ];
        $where = array_merge($where, $this->where);
        if (!array_key_exists('pagina', $where)) {
            $where['pagina'] = 1;
        }
        if (!array_key_exists('quantidade', $where)) {
            $where['quantidade'] = 24;
        }
        if (array_key_exists('acessado', $where)) {
            $where['mais_acessado'] = $where['acessado'];
            unset($where['acessado']);
        }
        if (!array_key_exists('ordem', $where)) {
            $where['ordem'] = (new Ordem(Ordem::FAVORITO))->valor();
        }
        $this->where = $where;
    }
}
