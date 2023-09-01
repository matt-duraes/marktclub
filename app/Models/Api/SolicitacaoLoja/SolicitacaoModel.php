<?php

namespace App\Models\Api\SolicitacaoLoja;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

class SolicitacaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param Pagina          $pagina
     * @param Quantidade|null $quantidade
     * @param Ordem|null      $ordem
     * @param Status|null     $status
     */
    public function __construct(
        private readonly Pagina $pagina,
        private readonly Quantidade $quantidade = new Quantidade(null),
        private readonly Ordem $ordem = new Ordem(null),
        private readonly Status $status = new Status(null)
    ) {
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'nome', 'origem', 'data_criacao', 'status'])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $dados
     *
     * @return array
     */
    private function montarRetorno(array $dados): array
    {
        $retorno = [];
        $Origem = new Origem();
        $Status = new Status();
        foreach ($dados as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'nome'         => $r->nome,
                'origem'       => $Origem->indice($r->origem),
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }
}
