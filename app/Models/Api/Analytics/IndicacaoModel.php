<?php

namespace App\Models\Api\Analytics;

use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

class IndicacaoModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param Data              $dataInicial
     * @param Data              $dataFinal
     * @param array|string|null $empresa    Filtra por Empresa
     * @param array|string|null $subempresa Filtra por Subempresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Data $dataInicial = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly array|string|null $empresa = null,
        private readonly array|string|null $subempresa = null
    ) {
        $this->setarIdEmpresa();
        $this->setarIdSubempresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    /**
     * @return array|array[]
     * @throws Excecao
     */
    public function gerarRelatorio(): array
    {
        $analytics = $this
            ->where($this->pegarWherePadrao(colunaTabela: 'data_criacao'), false)
            ->order('data_criacao')
            ->read();

        if (empty($analytics)) {
            return $this->retornarListaZerada();
        }

        return $this->montarRelatorio($analytics);
    }

    /**
     * @param array $analytics
     *
     * @return array
     */
    public function montarRelatorio(array $analytics): array
    {
        $concluido = 0;
        $cancelado = 0;
        $prospeccao = 0;
        $semVinculo = 0;
        $total = 0;
        $Status = new Status();
        foreach ($analytics as $item) {
            /*if ($item->id_admin_empresa == 1) {
                continue;
            }*/
            $total++;
            if ($Status->indice($item->status) === Status::CONCLUIDO) {
                $concluido++;
            } elseif ($Status->indice($item->status) === Status::CANCELADO) {
                $cancelado++;
            } elseif ($Status->indice($item->status) === Status::ANDAMENTO) {
                $prospeccao++;
            } else {
                $semVinculo++;
            }
        }
        return [
            'total'      => [
                'numero'      => $total,
                'porcentagem' => porcentagem($total, $total)
            ],
            'concluido'  => [
                'numero'      => $concluido,
                'porcentagem' => porcentagem($concluido, $total)
            ],
            'cancelado'  => [
                'numero'      => $cancelado,
                'porcentagem' => porcentagem($cancelado, $total)
            ],
            'prospeccao' => [
                'numero'      => $prospeccao,
                'porcentagem' => porcentagem($prospeccao, $total)
            ],
            'semVinculo' => [
                'numero'      => $semVinculo,
                'porcentagem' => porcentagem($semVinculo, $total)
            ]
        ];
    }

    /**
     * @return array[]
     */
    private function retornarListaZerada(): array
    {
        return [
            'total'      => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'concluido'  => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'cancelado'  => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'prospeccao' => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'semVinculo' => [
                'numero'      => 0,
                'porcentagem' => 0
            ]
        ];
    }
}
