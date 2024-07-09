<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use ApiModel\Endereco\RaioModel;
use App\Classes\ParceiroLoja\Ordem;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use App\Models\Api\ParceiroLoja\Trait\WhereTrait;
use App\Models\Api\ParceiroLoja\Trait\ListarCampoTrait;
use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeModelTrait;

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
    use PropriedadeModelTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public Ordem $ordem;

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
