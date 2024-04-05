<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Botao;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use ApiModel\Endereco\RaioModel;
use System\Trait\Model\OrdemTrait;
use App\Classes\ParceiroLoja\Ordem;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use App\Classes\ParceiroLoja\TipoEstabelecimento;
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
    private array $idMaisAcessado = [];
    public string $equipe;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public Botao $favorito;
    public Categoria $categoria;
    public string $subcategoria;
    public TipoEstabelecimento $tipo_estabelecimento;
    public string $pesquisa;
    public TipoLoja $tipo_loja;
    public Status $status;
    public Ordem $ordem;
    public Botao $mais_acessado;
    public float $latitude;
    public float $longitude;
    public array $endereco_estado;
    public string $empresa;

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
            $dado->order($this->pegarOrdem());
        }

        // // FAVORITO
        // $dado
        //     ->tabela(TABELA_PARCEIRO_FAVORITO)
        //     ->campo([['id_parceiro_loja', '!favorito']]);
        // if ($this->pExiste('favorito') && $this->favorito->valor() == $this->favorito::SIM) {
        //     $dado->join('id_parceiro_loja', 'id');
        // } else {
        //     $dado->leftJoin('id_parceiro_loja', 'id');
        // }

        // MAPA
        if (
            $this->pExiste('latitude') &&
            $this->pExiste('longitude') &&
            !empty($this->latitude) &&
            !empty($this->longitude)
        ) {
            $Raio = new RaioModel($this->latitude, $this->longitude);
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
        $where = $this->idEmpresa == 1 ? [] : [
            ['id_admin_empresa', 'json', $this->idEmpresa]
        ];

        $Where = new Where($this, $where);
        $Where
            ->linha(propriedade: 'tipo_loja')
            ->seValido(propriedade: 'categoria', callback: function () use ($Where) {
                $Where->manual([
                    'OR',
                    ['categoria_principal', $this->categoria->numero()],
                    ['categoria_lista', 'json', $this->categoria->numero()]
                ]);
            })
            ->seVazio(propriedade: 'subcategoria', vazio: false, callback: function () use ($Where) {
                $tag = $this->pegarIdSubCategoria();
                $Where->linha(propriedade: 'subcategoria_lista', condicao: 'json', valor: $tag);
            })
            ->seVazio(propriedade: 'pesquisa', vazio: false, callback: function () use ($Where) {
                $pesquisa = '%' . $this->pesquisa . '%';
                $Where->manual([
                    'OR',
                    ['titulo', 'like', $pesquisa],
                    ['subcategoria_tag', 'like', $pesquisa]
                ]);
            })
            ->linha('equipe', campo: 'id_usuario_equipe', valor: $this->pegarIdEquipe())
            ->linha('tipo_estabelecimento')
            ->seBotao('mais_acessao', callback: function () use ($Where) {
                $this->idMaisAcessado = (new MaisAcessadoModel($this->idEmpresa, $this->pegarQuantidade()))->id;
                $Where->linha(propriedade: 'id', condicao: 'in', valor: $this->idMaisAcessado);
            })
            ->linha('endereco_estado', 'json')
            ->linha('status');
        return $Where;
    }

    private function pegarIdEquipe()
    {
        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            return null;
        }
        return (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe);
    }

    private function pegarIdSubCategoria()
    {
        $tag = $this->subcategoria;
        if (empty($tag)) {
            return '';
        }
        return (new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA))->pegarCampoPor('id', ['url', $tag]);
    }
}
