<?php

namespace App\Models\Api\Analytics;

use Helpers\OrmHelper;
use ORM\ORM;
use Http\Request;
use Helpers\DataHelper;

final class LojaVendaModel extends ORM
{
    protected string $ormTabela = TABELA_ANALYTICS_LOJA_VENDA;
    private int $idEmpresa;
    private string $de;
    private string $ate;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->idEmpresa = TOKEN['empresa']->id;
        $this->pegarDataBusca();
    }

    public function listarDados(): array
    {
        $dado = $this
            ->campo(['id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'])
            ->where($this->pegarWhere(), false)
            ->order('data_criacao', 'DESC')
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->campo(['titulo'], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        if (!$dado) {
            return [];
        }

        return $this->montarDado($dado);
    }

    private function pegarWhere()
    {
        $whereData = ['data_relatorio', 'between', [$this->de, $this->ate]];

        if (empty($this->request->empresa)) {
            return [
                $whereData,
                ['id_admin_empresa', $this->idEmpresa]
            ];
        }
        $empresaUuid = $this->request->empresa;
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);

        $empresaId = [];
        foreach ($empresaUuid as $e) {
            $empresaId[] = $ormHelper->pegarIdPeloUuid($e);
        }
        return [
            $whereData,
            ['id_admin_empresa', 'in', $empresaId]
        ];
    }

    public function montarDado($r): array
    {
        return [
            'venda_mes'   => $this->montarRelatorioPorMes($r),
            'venda_loja'  => $this->montarVendaPorLoja($r),
            'ticket_loja' => $this->montarTicketPorLoja($r)
        ];
    }

    private function pegarDataBusca()
    {
        $this->de = dataPrimeiroDiaMes($this->request->de . ' 00:00:00', 'Y-m-d H:i:s');
        $this->ate = dataUltimoDiaMes($this->request->ate . ' 23:59:59', 'Y-m-d H:i:s');
    }

    private function montarRelatorioPorMes($lista)
    {
        $de = dataBanco($this->de);
        $ateExplode = explode('-', dataBanco($this->ate));
        $ate = $ateExplode[0] . '-' . $ateExplode[1] . '-01';

        $Data = new DataHelper();
        $dado = [];

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
                    'valor_venda'     => 0,
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
                'loja'        => $r->parceiro_titulo,
                'total'       => !empty($r->valor_venda) ? number_format($r->valor_venda, '2', ',', '.') : '0.00',
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
                    'ticket'          => 0,
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
                'loja'        => $r->parceiro_titulo,
                'total'       => !empty($r->ticket) ? number_format($r->ticket, '2', ',', '.') : '0.00',
                'porcentagem' => porcentagem($r->ticket, $total)
            ];
        }
        return $retorno;
    }
}
