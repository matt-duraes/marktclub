<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

final class AcessoDiaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_ACESSO_DIA;

    /**
     * @param Data              $dataInicial
     * @param Data              $dataFinal
     * @param array|string|null $empresa
     * @param array|string|null $subempresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Data $dataInicial = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly array|string|null $empresa = null,
        private readonly array|string|null $subempresa = null
    ) {
        $this->validarRequest();
        $this->setarIdEmpresa();
        $this->setarIdSubempresa();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if ($this->dataInicial->vazio()) {
            mensagemErro('Campo inválido!', 'A data de início da busca é obrigatória.');
        } elseif ($this->dataFinal->vazio()) {
            mensagemErro('Campo inválido!', 'A data final da busca é obrigatória.');
        } elseif (!$this->dataInicial->valido()) {
            mensagemErro('Campo inválido!', 'A data de início da busca não é válida.');
        } elseif (!$this->dataFinal->valido()) {
            mensagemErro('Campo inválido!', 'A data final da busca não é válida.');
        }
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function gerarRelatorio(): array
    {
        $analytics = $this
            ->campo([
                'quantidade_total', 'quantidade_unico', 'data_acesso'
            ])
            ->where($this->pegarWherePadrao())
            ->read();
        return $this->montarRelatorio($analytics);
    }

    /**
     * @param array $analytics
     *
     * @return array
     */
    private function montarRelatorio(array $analytics): array
    {
        $dataInicial = $this->dataInicial->data();
        $dataFinal = $this->dataFinal->data();

        $relatorio = [
            $dataInicial => [
                'total' => 0,
                'unico' => 0
            ]
        ];

        for ($i = 0; $i < 367; $i++) {
            $dataIncrementada = dataAdicionar($dataInicial, $i, 'dia', 'd/m/Y');
            $relatorio[$dataIncrementada] = [
                'data'  => $dataIncrementada,
                'total' => 0,
                'unico' => 0
            ];
            if ($dataIncrementada == $dataFinal) {
                break;
            }
        }

        $somas_por_data = [];
        foreach ($analytics as $objeto) {
            $data_acesso = $objeto->data_acesso;
            if (!isset($somas_por_data[$data_acesso])) {
                $somas_por_data[$data_acesso] = [
                    'data_acesso'      => $data_acesso,
                    'quantidade_total' => $objeto->quantidade_total,
                    'quantidade_unico' => $objeto->quantidade_unico
                ];
            } else {
                $somas_por_data[$data_acesso]['quantidade_total'] += $objeto->quantidade_total;
                $somas_por_data[$data_acesso]['quantidade_unico'] += $objeto->quantidade_unico;
            }
        }

        $analytics = array_values($somas_por_data);
        foreach ($analytics as $item) {
            $data = dataBr($item['data_acesso']);
            $relatorio[$data]['data'] = $data;
            $relatorio[$data]['unico'] = $item['quantidade_unico'];
            $relatorio[$data]['total'] = $item['quantidade_total'];
        }
        return array_values($relatorio);
    }
}
