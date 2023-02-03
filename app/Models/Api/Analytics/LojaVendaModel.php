<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use Helpers\DataHelper;

final class LojaVendaModel extends ORM
{
    protected string $_tabela = TABELA_ANALYTICS_LOJA_VENDA;

    private int $idEmpresa;
    private Data $de;
    private Data $ate;

    public function __construct(
        private int $quantidade
    ) {
        parent::__construct();

        if ($quantidade == 1) {
            mensagemErro('Campo inválido!', 'A quantidade de meses deve ser maior que 1.');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->pegarDataBusca();
    }

    public function listarDados(): array
    {
        $de = $this->converterData($this->de->date());
        $ate = $this->converterData($this->ate->date());

        $dado = $this
            ->campo(['id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'])
            ->where([
                ['id_admin_empresa', $this->idEmpresa],
                ['data_relatorio', 'between', [$de, $ate]]
            ])
            ->order('data_criacao', 'DESC')
            ->tabela(TABELA_PARCEIRO_NOVO)
            ->campo(['titulo'], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        if (!$dado) {
            return [];
        }

        return $this->montarDado($dado);
    }

    public function montarDado($r): array
    {
        return [
            'venda_mes' => $this->montarRelatorioPorMes($r),
            'venda_loja' => $this->montarVendaPorLoja($r),
            'ticket_loja' => $this->montarTicketPorLoja($r)
        ];
    }

    private function pegarDataBusca()
    {
        $mesInicial = date('Y-m') . '-01';
        $inicio = 0;
        $final = $this->quantidade;
        if (!$this->existe(['data_relatorio', $mesInicial])) {
            $inicio = 1;
            $final++;
        }

        $this->de = new Data(dataRemover($mesInicial, $final, 'mes'));
        $this->ate = new Data(dataRemover($mesInicial, $inicio, 'mes'));
    }

    private function montarRelatorioPorMes($lista)
    {
        $de = $this->converterData($this->de->date());
        $ate = $this->converterData($this->ate->date());

        $Data = new DataHelper();
        $dado = [];

        for ($i = 0; $i < 13; $i++) {
            $data = $Data->valor($de)->adicionar($i, 'mes')->formato('Y-m-d');
            $dado[$data] = object([
                'data' => $Data->valor($data)->formato('m/Y'),
                'valor' => 0,
                'ticket' => 0,
                'venda' => 0
            ]);

            if ($data == $ate) {
                break;
            }
        }
        foreach ($lista as $r) {
            $dado[$r->data_relatorio]->valor += $r->valor_venda;
            $dado[$r->data_relatorio]->venda += $r->numero_transacao;
        }

        $retorno = [];
        foreach ($dado as $r) {
            if (!empty($r->valor) && !empty($r->venda)) {
                $r->ticket = number_format($r->valor / $r->venda, 2, '.', '');
            }
            $retorno[] = $r;
        }
        return $retorno;
    }

    private function montarVendaPorLoja($lista)
    {
        $dado = [];
        $total = 0;
        foreach ($lista as $r) {
            $total += $r->valor_venda;
            if (!array_key_exists($r->id_parceiro_loja, $dado)) {
                $dado[$r->id_parceiro_loja] = object([
                    'parceiro_titulo' => $r->parceiro_titulo,
                    'valor_venda' => 0,
                ]);
            }
            $dado[$r->id_parceiro_loja]->valor_venda += $r->valor_venda;
        }

        usort($dado, function ($a, $b) {
            $a = $a->valor_venda;
            $b = $b->valor_venda;
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? 1 : -1;
        });

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'loja' => $r->parceiro_titulo,
                'total' => !empty($r->valor_venda) ? number_format($r->valor_venda, '2', ',', '.') : "0.00",
                'porcentagem' => porcentagem($r->valor_venda, $total)
            ];
        }
        return $retorno;
    }

    private function montarTicketPorLoja($lista)
    {
        $dado = [];
        $total = 0;
        foreach ($lista as $r) {
            $ticket = !empty($r->valor_venda) && !empty($r->numero_transacao) ?
                $r->valor_venda / $r->numero_transacao : 0;
            $total += $ticket;

            if (!array_key_exists($r->id_parceiro_loja, $dado)) {
                $dado[$r->id_parceiro_loja] = object([
                    'parceiro_titulo' => $r->parceiro_titulo,
                    'ticket' => 0,
                ]);
            }
            $dado[$r->id_parceiro_loja]->ticket += $ticket;
        }

        usort($dado, function ($a, $b) {
            $a = $a->ticket;
            $b = $b->ticket;
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? 1 : -1;
        });

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'loja' => $r->parceiro_titulo,
                'total' => !empty($r->ticket) ? number_format($r->ticket, '2', ',', '.') : "0.00",
                'porcentagem' => porcentagem($r->ticket, $total)
            ];
        }
        return $retorno;
    }

    private function converterData(string $data)
    {
        $separador = str_contains($data, '-') ? '-' : '/';
        $data = explode($separador, $data);
        return $separador == '-' ? $data[0] . '-' . $data[1] . '-01' : '01/' . $data[1] . '/' . $data[2];
    }
}
