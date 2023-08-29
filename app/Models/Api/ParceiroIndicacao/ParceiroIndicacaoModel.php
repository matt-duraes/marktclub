<?php

namespace App\Models\Api\ParceiroIndicacao;

use App\Classes\ParceiroIndicacao\Ordem;
use App\Classes\ParceiroIndicacao\Status;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class ParceiroIndicacaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_INDICACAO;

    /**
     * @param Pagina          $pagina
     * @param Quantidade|null $quantidade
     * @param Ordem|null      $ordem
     * @param Status|null     $status
     */
    public function __construct(
        private readonly Pagina $pagina,
        private readonly ?Quantidade $quantidade = null,
        private readonly ?Ordem $ordem = null,
        private readonly ?Status $status = null
    ) {
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dados = $this
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
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
        foreach ($dados as $dado) {
            $retorno[] = [
                'id'               => $dado->uuid,
                'nome'             => $dado->nome,
                'telefone'         => $dado->telefone,
                'email'            => $dado->email,
                'mensagem'         => $dado->mensagem,
                'status'           => (new Status())->indice($dado->status),
                'data_criacao'     => $dado->data_criacao,
                'data_atualizacao' => $dado->data_atualizacao
            ];
        }
        return $retorno;
    }
}
