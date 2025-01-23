<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Exception;
use Helpers\DataHelper;
use Helpers\OrmHelper;
use Modules\Data;
use ORM\ORM;

final class LojaVendaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_LOJA_VENDA;
    private string $primeiroDiaMes;
    private string $ultimoDiaMes;
    private array $somado = [];
    private int|float $total = 0;

    /**
     * @param Data              $dataInicial
     * @param Data              $dataFinal
     * @param array|string|null $empresa
     * @param array|string|null $subempresa
     * @param array|string|null $parceiro
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Data $dataInicial = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly array|string|null $empresa = null,
        private readonly array|string|null $subempresa = null,
        private readonly array|string|null $parceiro = null
    ) {
        $this->setarIdEmpresa();
        $this->setarIdSubempresa();
        $this->setarIdUsuario();
        $this->pegarDiasMes();
        parent::__construct();
    }

    private function pegarDiasMes(): void
    {
        $this->primeiroDiaMes = dataPrimeiroDiaMes($this->dataInicial . ' 00:00:00', 'Y-m-d H:i:s');
        $this->ultimoDiaMes = dataUltimoDiaMes($this->dataFinal . ' 23:59:59', 'Y-m-d H:i:s');
    }

    /**
     * @return array
     * @throws Excecao
     * @throws Exception
     */
    public function gerarRelatorio(): array
    {
        $analytics = $this
            ->campo([
                'id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'
            ])
            ->where($this->pegarWhere(), false)
            ->order('data_criacao')
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->campo([
                'titulo'
            ], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        if (empty($analytics)) {
            return [];
        }
        return $this->montarRelatorio($analytics);
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhere(): array
    {
        $where = $this->pegarWherePadrao(false);
        $whereParceiro = $this->pegarWhereParceiro();
        if (!empty($whereParceiro)) {
            $where[] = $whereParceiro;
        }

        if (!empty($this->primeiroDiaMes) && !empty($this->ultimoDiaMes)) {
            $where[] = ['data_relatorio', 'between', [$this->primeiroDiaMes, $this->ultimoDiaMes]];
        }
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereParceiro(): array
    {
        if (empty($this->parceiro)) {
            return [];
        }

        $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
        if (is_string($this->parceiro)) {
            $parceiroId = $ormHelper->pegarIdPeloUuid($this->parceiro);
            return ['id_parceiro_loja', $parceiroId];
        }
        return ['id_parceiro_loja', 'in', $ormHelper->mudarListaUuidParaId($this->parceiro)];
    }

    /**
     * @param array $analytics
     *
     * @return array
     * @throws Exception
     */
    private function montarRelatorio(array $analytics): array
    {
        $this->somarTodos($analytics);
        return [
            'venda_mes'   => $this->montarRelatorioPorMes($analytics),
            'venda_loja'  => $this->montarVendaPorLoja(),
            'ticket_loja' => $this->montarTicketPorLoja()
        ];
    }

    /**
     * @param array $analytics
     *
     */
    private function somarTodos(array $analytics): void
    {
        $dado = [];
        $total = 0;
        foreach ($analytics as $item) {
            $total += $item->valor_venda;
            if (!array_key_exists($item->id_parceiro_loja, $dado)) {
                $dado[$item->id_parceiro_loja] = object([
                    'parceiro_titulo'  => $item->parceiro_titulo,
                    'numero_transacao' => 0,
                    'valor_venda'      => 0
                ]);
            }
            $dado[$item->id_parceiro_loja]->valor_venda += $item->valor_venda;
            $dado[$item->id_parceiro_loja]->numero_transacao += $item->numero_transacao;
        }

        usort($dado, function ($a, $b) {
            $a = $a->valor_venda;
            $b = $b->valor_venda;
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? 1 : -1;
        });

        $this->total = $total;
        $this->somado = $dado;
    }

    /**
     * @param array $analytics
     *
     * @return array
     * @throws Exception
     */
    private function montarRelatorioPorMes(array $analytics): array
    {
        $de = dataBanco($this->primeiroDiaMes);
        $ateExplode = explode('-', dataBanco($this->ultimoDiaMes));
        $ate = $ateExplode[0] . '-' . $ateExplode[1] . '-01';

        $dado = [];
        $Data = new DataHelper();
        for ($i = 0; $i < 13; $i++) {
            $data = $Data->valor($de)->adicionar($i, 'mes')->formato('Y-m-d');
            $dado[$data] = object([
                'data'   => $Data->valor($data)->formato('m/Y'),
                'valor'  => 0,
                'ticket' => 0,
                'venda'  => 0
            ]);
            if ($data == $ate) {
                break;
            }
        }

        foreach ($analytics as $item) {
            if (!array_key_exists($item->data_relatorio, $dado)) {
                continue;
            }
            $dado[$item->data_relatorio]->valor += $item->valor_venda;
            $dado[$item->data_relatorio]->venda += $item->numero_transacao;
        }

        $retorno = [];
        foreach ($dado as $item) {
            if (!empty($item->valor) && !empty($item->venda)) {
                $item->ticket = number_format($item->valor / $item->venda, 2, '.', '');
            }
            $retorno[] = $item;
        }
        return $retorno;
    }

    /**
     * @return array
     */
    private function montarVendaPorLoja(): array
    {
        $dado = $this->somado;
        $retorno = [];
        foreach ($dado as $item) {
            $total = number_format($item->valor_venda, '2', ',', '.');
            $retorno[] = [
                'loja'             => $item->parceiro_titulo,
                'total'            => !empty($item->valor_venda) ? $total : '0.00',
                'porcentagem'      => porcentagem($item->valor_venda, $this->total),
                'numero_transacao' => !empty($item->numero_transacao) ? $item->numero_transacao : 0
            ];
        }
        return $retorno;
    }

    /**
     * @return array
     */
    private function montarTicketPorLoja(): array
    {
        $dado = [];
        foreach ($this->somado as $item) {
            $dado[] = object([
                'parceiro_titulo' => $item->parceiro_titulo,
                'ticket'          => !empty($item->valor_venda) && !empty($item->numero_transacao)
                    ? $item->valor_venda / $item->numero_transacao
                    : 0
            ]);
        }

        $retorno = [];
        foreach ($dado as $item) {
            $total = number_format($item->ticket, '2', ',', '.');
            $retorno[] = [
                'loja'        => $item->parceiro_titulo,
                'total'       => !empty($item->ticket) ? $total : '0.00',
                'porcentagem' => porcentagem($item->ticket, $this->total)
            ];
        }
        return $retorno;
    }
}
