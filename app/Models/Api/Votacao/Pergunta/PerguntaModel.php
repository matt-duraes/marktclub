<?php

namespace App\Models\Api\Votacao\Pergunta;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\PaginaTrait;
use App\Classes\Votacao\Pergunta\Tipo;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Votacao\Trait\idVotacaoTrait;

final class PerguntaModel extends ORM implements ModelListarInterface
{
    use idVotacaoTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_VOTACAO_PERGUNTA;
    public string $votacao;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'tipo'])
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

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'texto'  => $r->texto,
                'tipo'   => $Tipo->indice($r->tipo)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where->naoVazio('votacao', function () use ($Where) {
            $Where->manual(['id_votacao_dado', $this->idVotacao($this->votacao)]);
        });
        return $Where;
    }
}
