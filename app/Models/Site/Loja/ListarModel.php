<?php

namespace App\Models\Site\Loja;

use stdClass;
use Modules\Botao;
use Modules\Inteiro;
use Helpers\ListaHelper;
use Modules\EnderecoEstado;
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
    private array $where = [];
    private bool $cache = false;

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
        private null|string|float $latitude = null,
        private null|string|float $longitude = null,
        private Botao $acessado = new Botao(Botao::NAO),
        private EnderecoEstado $estado = new EnderecoEstado(null)
    ) {
        parent::__construct();
        $this->setarWhere();
        $this->setarSessao();
    }

    private function setarSessao()
    {
        if (!sessaoExiste('LOJA')) {
            $this->setarSessaoPadrao();
        }
        $sessao = sessao('LOJA');
        $sessaoLista = $sessao['lista'];
        $sessaoWhere = $sessao['where'];
        $where = $this->where;
        unset($sessaoWhere['pagina'], $where['pagina']);

        // pp($sessaoWhere);
        // pp($where);
        // vde($sessaoWhere == $where);
        if ($sessaoWhere == $where && !empty($sessaoLista) && $this->pagina->vazio()) {
            $this->cache = true;
            return;
        } elseif ($this->pagina->vazio()) {
            $this->setarSessaoPadrao();
        }
    }

    private function setarSessaoPadrao()
    {
        sessao('LOJA', [
            'where'     => $this->where,
            'lista'     => [],
            'mapa'      => [],
            'paginacao' => [],
            'registro'  => []
        ]);
    }

    private function adicionarRegistroSessao($lista, $paginacao, $registro)
    {
        $sessao = sessao('LOJA');
        $listaAtual = $sessao['lista'];
        $mapaAtual = $sessao['mapa'];
        sessao('LOJA', [
            'where'     => $this->where,
            'lista'     => array_merge($listaAtual, $lista),
            'mapa'      => array_merge($mapaAtual, $this->mapa),
            'paginacao' => $paginacao,
            'registro'  => $registro
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {
        if ($this->cache) {
            return $this->pegarRegistroSessao();
        }

        $dado = $this
            ->validar(login: true)
            ->json($this->where)
            ->get('/parceiro-loja')
            ->object();

        $lista = $this->montarLista($dado->dado->lista ?? []);
        $paginacao = $dado->dado->pagina ?? [];
        $registro = $dado->dado->registro ?? [];
        $this->adicionarRegistroSessao($lista, $paginacao, $registro);

        return (object)[
            'tipo'      => $this->tipo->indice(),
            'lista'     => $lista,
            'mapa'      => $this->mapa,
            'paginacao' => $paginacao,
            'registro'  => $registro
        ];
    }

    private function pegarRegistroSessao()
    {
        $sessao = sessao('LOJA');
        return (object)[
            'tipo'      => $this->tipo->indice(),
            'lista'     => $sessao['lista'],
            'mapa'      => $sessao['mapa'],
            'paginacao' => $sessao['paginacao'],
            'registro'  => $sessao['registro']
        ];
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
        if ($this->estado->valido()) {
            $where['estado'] = $this->estado->valor();
        }
        $this->where = $where;
    }
}
