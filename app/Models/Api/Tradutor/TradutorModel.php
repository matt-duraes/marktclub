<?php

namespace App\Models\Api\Tradutor;

use App\Classes\Geral\Status;
use App\Classes\PainelTradutor\Ordem;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class TradutorModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PAINEL_TRADUTOR;

    /**
     * @param Pagina     $pagina
     * @param Quantidade $quantidade
     * @param Ordem      $ordem
     * @param Status     $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDado();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDado(): void
    {
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'termo', 'traducao', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
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
     * @param array $traducoes
     *
     * @return array
     */
    private function montarRetorno(array $traducoes): array
    {
        if (empty($traducoes)) {
            return $traducoes;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($traducoes as $traducao) {
            $retorno[] = [
                'id'               => $traducao->uuid,
                'termo'            => $traducao->termo,
                'traducao'         => $traducao->traducao,
                'status'           => $Status->indice($traducao->status),
                'data_criacao'     => $traducao->data_criacao,
                'data_atualizacao' => $traducao->data_atualizacao
            ];
        }
        return $retorno;
    }
}
