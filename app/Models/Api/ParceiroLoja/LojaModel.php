<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Http\Request;
use Helpers\OrmHelper;
use Modules\EnderecoEstado;
use ApiModel\Endereco\RaioModel;
use App\Classes\ParceiroLoja\Tipo;
use System\Classes\Endereco\Local;
use System\Trait\Model\OrdemTrait;
use App\Classes\ParceiroLoja\Ordem;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Classes\ParceiroLoja\Estabelecimento;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use App\Models\Api\ParceiroLoja\Trait\ListarCampoTrait;
use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;

class LojaModel extends ORM implements ModelListarInterface
{
    use EmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;
    use ListarCampoTrait;
    use MontarRetornoTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    private int $idEmpresa;
    private array $favorito = [];
    private array $idMaisAcessado = [];

    public function __construct(
        private ?Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo($this->pegarCampo())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false);

        // ORDEM
        if (!empty($this->idMaisAcessado)) {
            $dado->orderTexto('FIELD(`' . $this->ormTabela . '`.`id`, ' . implode(',', $this->idMaisAcessado) . ')');
        } else {
            $dado->order($this->pegarOrdem(new Ordem()));
        }

        // FAVORITO
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
                ->join('uuid', 'id_vinculo')
                ->campo(['latitude', 'longitude'])
                ->where([
                    ['local_principal', TABELA_PARCEIRO_LOJA],
                    ['local_secundario', 'clube'],
                    ['latitude', 'between', $Raio->latitude],
                    ['longitude', 'between', $Raio->longitude],
                ]);
        }
        $dado = $dado->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
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

    protected function pegarWhere(): Where
    {
        $where = [
            ['empresa', 'json', $this->idEmpresa]
        ];
        $Where = new Where($this, $where);
        $Where
            ->linha(campo: 'tipo')
            ->seValido('categoria_principal', function() use($Where) {
                $Where->manual([
                    'OR',
                    ['categoria_principal', $categoria->numero()],
                    ['categoria_lista', 'json', $categoria->numero()]
                ])
            })
            ->seVazio('subcategoria', vazio: false, function() use ($Where) {
                $tag = $this->pegarIdSubCategoria();
                $Where->linha(campo: 'subcategoria_lista', valor: $tag . '%%');
            })
            ->seVazio(campo: 'pesquisa', vazio: false, function() use ($Where) {
                $pesquisa = $this->pesquisa;
                $Where->manual = [
                    'OR',
                    ['titulo', 'like', '%' . $pesquisa . '%'],
                    ['subcategoria_tag', 'like', '%' . $pesquisa . '%']
                ];
            })
            ->linha('tipo_estabelecimento')
            ->seSim('mais_acessao', function() use ($Where) {
                $this->idMaisAcessado = (new MaisAcessadoModel($this->idEmpresa, $this->pegarQuantidade()))->id;
                if (!empty($this->idMaisAcessado)) {
                    $where[] = ['id', 'in', $this->idMaisAcessado];
                }
            })
            ->linha('endereco_estado', 'json');

        $status = new Status($this->request->status);
        if ($this->idEmpresa != 1 || !$status->valido()) {
            $Where->manual(['status', (new Status(Status::CONCLUIDO))->numero()]);
        } elseif ($status->valido()) {
            $Where->manual(['status', $status->numero()]);
        }

        return $Where;
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
