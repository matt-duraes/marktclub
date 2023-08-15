<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Http\Request;
use Helpers\OrmHelper;
use App\Classes\ParceiroLoja\Tipo;
use System\Trait\Model\OrdemTrait;
use App\Classes\ParceiroLoja\Ordem;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroLoja\Status;
use App\Classes\SistemaEndereco\Local;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\SistemaEndereco\Tabela;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Classes\ParceiroLoja\Estabelecimento;
use App\Models\Api\SistemaEndereco\RaioModel;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;

class LojaModel extends ORM implements ModelListarInterface
{
    use EmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    private int $idEmpresa;
    private array $favorito = [];
    private array $idMaisAcessado = [];

    public function __construct(
        private ?Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['id', 'cod', 'titulo', 'url', 'tipo', 'desconto', 'imagem', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false);
        //Ordem
        if (!empty($this->idMaisAcessado)) {
            $dado->orderTexto('FIELD(`' . $this->ormTabela . '`.`id`, ' . implode(',', $this->idMaisAcessado) . ')');
        } else {
            $dado->order($this->pegarOrdem(new Ordem()));
        }

        // Favorito
        $dado
            ->tabela(TABELA_PARCEIRO_FAVORITO)
            ->campo([['id_parceiro_loja', '!favorito']]);
        if ($this->request->favorito == 'sim') {
            $dado->join('id_parceiro_loja', 'id');
        } else {
            $dado->leftJoin('id_parceiro_loja', 'id');
        }
        // MAPA
        if (!empty($this->request->latitude) && !empty($this->request->longitude)) {
            $Raio = new RaioModel($this->request->latitude, $this->request->longitude);
            $dado
                ->tabela(TABELA_SISTEMA_ENDERECO)
                ->join('cod', 'cod')
                ->campo(['latitude', 'longitude'])
                ->where([
                    ['tabela', Tabela::LOJA],
                    ['local', (new Local(Local::CLUBE))->numero()],
                    ['latitude', 'between', $Raio->latitude],
                    ['longitude', 'between', $Raio->longitude],
                ]);
        }
        $dado = $dado->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno($lista): array
    {
        if (!$lista) {
            return [];
        }

        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();

        foreach ($lista as $r) {
            $retorno[$r->id] = [
                'id'        => $r->cod,
                'titulo'    => $r->titulo,
                'desconto'  => $r->desconto,
                'imagem'    => LINK_ARQUIVO . '/parceiro/' . $r->imagem,
                'url'       => $r->url,
                'tipo'      => $Tipo->indice($r->tipo),
                'favorito'  => !empty($r->favorito) ? 'sim' : 'nao',
                'status'    => $Status->indice($r->status)
            ];
        }
        if (object_key_exists('latitude', $lista[0])) {
            $retorno = $this->montarListaGeolocalizacao($retorno, $lista);
        }
        return array_values($retorno);
    }

    private function montarListaGeolocalizacao($retorno, $lista)
    {
        foreach ($lista as $r) {
            $retorno[$r->id]['geolocalizacao'][] = [
                'latitude'  => $r->latitude,
                'longitude' => $r->longitude
            ];
        }
        return $retorno;
    }

    protected function validarRequest(): void
    {
        $tipo = new Tipo($this->request->tipo);
        if (!$tipo->vazio() && !$tipo->valido()) {
            mensagemErro('Erro!', 'O campo tipo não é um valor válido.');
        }
        $estabelecimento = new Estabelecimento($this->request->estabelecimento);
        if (!$estabelecimento->vazio() && !$estabelecimento->valido()) {
            mensagemErro('Erro!', 'O campo estabelecimento não é um valor válido.');
        }
        $categoria = new Categoria($this->request->categoria);
        if (!$categoria->vazio() && !$categoria->valido()) {
            mensagemErro('Erro!', 'O campo categoria não é um valor válido.');
        }
        $status = new Status($this->request->status);
        if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Erro!', 'O campo status não é um valor válido.');
        }
    }

    protected function pegarWhere(): array
    {
        $where = [
            ['empresa', 'LIKE', '%"' . $this->idEmpresa . '"%']
        ];

        $tipo = new Tipo($this->request->tipo);
        if ($tipo->valido()) {
            $where[] = ['tipo', $tipo->numero()];
        }

        $categoria = new Categoria($this->request->categoria);
        if ($categoria->valido()) {
            $where[] = [
                'OR',
                ['categoria_principal', $categoria->numero()],
                ['categoria_todas', 'like', '%"' . $categoria->numero() . '"%']
            ];
        }

        $tag = $this->pegarIdSubCategoria();
        if (!empty($tag)) {
            $where[] = ['tag_lista', 'like', '%"' . $tag . '"%'];
        }

        $pesquisa = $this->request->pesquisa;
        if (!empty($pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $pesquisa . '%'],
                ['tag', 'like', '%' . $pesquisa . '%']
            ];
        }

        $estabelecimento = new Estabelecimento($this->request->estabelecimento);
        if ($estabelecimento->valido()) {
            $where[] = ['estabelecimento', $estabelecimento->numero()];
        }

        if (!empty($this->request->mais_acessado)) {
            $this->idMaisAcessado = (new MaisAcessadoModel($this->idEmpresa, $this->pegarQuantidade()))->id;
        }
        if (!empty($this->idMaisAcessado)) {
            $where[] = ['id', 'in', $this->idMaisAcessado];
        }

        $status = new Status($this->request->status);
        if ($this->idEmpresa != 1 || !$status->valido()) {
            $where[] = ['status', (new Status(Status::CONCLUIDO))->numero()];
        } elseif ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }
        return $where;
    }

    private function pegarIdSubCategoria()
    {
        $tag = $this->request->subcategoria;
        if (empty($tag)) {
            return '';
        }
        return (new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA))->pegarCampoPor('id', ['url', $tag]);
    }
}
