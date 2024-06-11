<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
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
use App\Models\Api\ParceiroLoja\Trait\WhereTrait;

class LojaModel extends ORM implements ModelListarInterface
{
    use EmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;
    use ListarCampoTrait;
    use MontarRetornoTrait;
    use WhereTrait;

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
    public string $titulo;
    public string $pesquisa;
    public TipoLoja $tipo_loja;
    public Status $status;
    public Ordem $ordem;
    public Botao $mais_acessado;
    public string $convenio_direto;
    public float $latitude;
    public float $longitude;
    public array $endereco_estado;
    public string $empresa;
    private bool $buscarFavorito = true;
    public string $convenio;
    public string $painel;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Data $data_publicacao_de;
    public Data $data_publicacao_ate;
    public Data $data_prospeccao_de;
    public Data $data_prospeccao_ate;
    public Data $data_problema_de;
    public Data $data_problema_ate;
    public Data $data_cancelado_de;
    public Data $data_cancelado_ate;
    public Data $data_auditoria_de;
    public Data $data_auditoria_ate;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo($this->pegarCampo())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false);

        if ($this->pExiste('favorito') && $this->favorito->valor() == $this->favorito::SIM) {
            $this->buscarFavorito = false;
            $dado
                ->tabela(TABELA_PARCEIRO_FAVORITO)
                ->campo([['id_parceiro_loja', '!favorito']])
                ->leftJoin('id_parceiro_loja', 'id')
                ->where(['id_usuario_cliente', $this->idUsuario]);
        } elseif (!empty($this->idMaisAcessado)) {
            $dado->orderTexto('FIELD(`' . $this->ormTabela . '`.`id`, ' . implode(',', $this->idMaisAcessado) . ')');
        } else {
            $dado->order($this->pegarOrdem());
        }

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
                ->join('id_vinculo', 'uuid')
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
}
