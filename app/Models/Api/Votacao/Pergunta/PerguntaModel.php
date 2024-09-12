<?php

namespace App\Models\Api\Votacao\Pergunta;

use App\Classes\Votacao\Pergunta\Tipo;
use App\Models\Api\Votacao\Trait\idVotacaoTrait;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use Where\Where;

final class PerguntaModel extends ORM implements ModelListarInterface
{
    use idVotacaoTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    public string $votacao;
    public Pagina $pagina;
    public Quantidade $quantidade;
    protected string $ormTabela = TABELA_VOTACAO_PERGUNTA;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'tipo', 'pode_nulo'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('ordem', 'ASC')
            ->read();

        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where->naoVazio('votacao', function () use ($Where) {
            $Where->manual(['id_votacao_dado', $this->idVotacao($this->votacao)]);
        });
        return $Where;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'texto'     => $r->texto,
                'tipo'      => $Tipo->indice($r->tipo),
                'pode_nulo' => !empty($r->pode_nulo) && $r->pode_nulo == '1' ? Botao::SIM : Botao::NAO
            ];
        }
        return $retorno;
    }
}
