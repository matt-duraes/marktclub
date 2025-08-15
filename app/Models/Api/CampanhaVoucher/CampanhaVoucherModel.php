<?php

namespace App\Models\Api\CampanhaVoucher;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\CampanhaVoucher\Ordem;
use App\Classes\CampanhaVoucher\Status;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class CampanhaVoucherModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_CAMPANHA_VOUCHER;

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
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
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
        $vouchers = $this
            ->campo([
                'uuid', 'data_resgate', 'data_vencimento', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $vouchers->lista = $this->montarRetorno($vouchers->lista);
        return $vouchers;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $vouchers
     *
     * @return array
     */
    private function montarRetorno(array $vouchers): array
    {
        if (empty($vouchers)) {
            return $vouchers;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($vouchers as $voucher) {
            $retorno[] = [
                'id'               => $voucher->uuid,
                'data_resgate'     => $voucher->data_resgate,
                'data_validade'    => $voucher->data_vencimento,
                'data_criacao'     => $voucher->data_criacao,
                'data_atualizacao' => $voucher->data_atualizacao,
                'status'           => $Status->indice($voucher->status)
            ];
        }
        return $retorno;
    }
}
