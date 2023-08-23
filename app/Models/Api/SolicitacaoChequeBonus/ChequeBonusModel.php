<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\Solicitacao\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ChequeBonusModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private Data $dataCriacaoDe = new Data(null),
        private Data $dataCriacaoAte = new Data(null),
        private Status $status = new Status(null),
        private ?string $empresa = null,
        private Ordem $ordem = new Ordem(null)
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montarDado($dado->lista ?? []);
        return $dado;
    }

    private function pegarWhere()
    {
        return [];
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id' => $r->uuid,
            ];
        }
        return $retorno;
    }
}
