<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SolicitacaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param Pagina     $pagina
     * @param Quantidade $quantidade
     * @param Ordem      $ordem
     * @param Status     $status
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
            ->campo([
                'uuid', 'nome', 'email', 'telefone',
                'origem', 'status', 'data_criacao', 'data_atualizacao'
            ])
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
        foreach ($dados as $r) {
            $retorno[] = [
                'id'               => $r->uuid,
                'nome'             => $r->nome,
                'email'            => $r->email,
                'telefone'         => $r->telefone,
                'origem'           => (new Origem())->indice($r->origem),
                'status'           => (new Status())->indice($r->status),
                'data_criacao'     => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao
            ];
        }
        return $retorno;
    }
}
