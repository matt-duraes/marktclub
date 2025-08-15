<?php

namespace App\Models\Api\Analytics;

use App\Classes\CampanhaVoucher\Status;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

class CampanhaVoucherModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_CAMPANHA_VOUCHER;

    /**
     * @param Data              $dataInicial
     * @param Data              $dataFinal
     * @param array|string|null $empresa Filtra por Empresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Data $dataInicial = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly array|string|null $empresa = null
    ) {
        $this->setarIdEmpresa();
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
            ->where($this->pegarWherePadrao(false, 'data_atualizacao', false), false)
            ->order('data_atualizacao')
            ->read();

        if (empty($analytics)) {
            return $this->retornarListaZerada();
        }

        return $this->montarRelatorio($analytics);
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
            'resgatado'  => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'vencido'    => [
                'numero'      => 0,
                'porcentagem' => 0
            ],
            'disponivel' => [
                'numero'      => 0,
                'porcentagem' => 0
            ]
        ];
    }

    /**
     * @param array $analytics
     *
     * @return array
     */
    public function montarRelatorio(array $analytics): array
    {
        $resgatado = 0;
        $vencido = 0;
        $disponivel = 0;
        $total = 0;
        $Status = new Status();
        foreach ($analytics as $item) {
            $total++;
            if ($Status->indice($item->status) === Status::RESGATADO) {
                $resgatado++;
            } elseif ($Status->indice($item->status) === Status::VENCIDO) {
                $vencido++;
            } else {
                $disponivel++;
            }
        }
        return [
            'total'      => [
                'numero'      => $total,
                'porcentagem' => porcentagem($total, $total)
            ],
            'resgatado'  => [
                'numero'      => $resgatado,
                'porcentagem' => porcentagem($resgatado, $total)
            ],
            'vencido'    => [
                'numero'      => $vencido,
                'porcentagem' => porcentagem($vencido, $total)
            ],
            'disponivel' => [
                'numero'      => $disponivel,
                'porcentagem' => porcentagem($disponivel, $total)
            ]
        ];
    }
}
