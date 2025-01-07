<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

final class DispositivoModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_DISPOSITIVO;

    /**
     * @param Data              $dataInicial Filtra por Data Inicial
     * @param Data              $dataFinal   Filtra por Data Final
     * @param array|string|null $empresa     Filtra por Empresa. String uma, Array varias
     * @param array|string|null $subempresa  Filtra por Subempresa. String uma, Array varias
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
                'quantidade', 'dispositivo'
            ])
            ->where($this->pegarWherePadrao())
            ->order('quantidade')
            ->limit(0, 20)
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
        $total = 0;
        foreach ($analytics as $item) {
            $total += $item->quantidade;
        }

        $relatorio = [];
        foreach ($analytics as $item) {
            if (array_key_exists($item->dispositivo, $relatorio)) {
                $relatorio[$item->dispositivo]['total'] += $item->quantidade;
                $relatorio[$item->dispositivo]['porcentagem'] = porcentagem(
                    $relatorio[$item->dispositivo]['total'],
                    $total
                );
                continue;
            }
            $relatorio[$item->dispositivo] = [
                'dispositivo' => $item->dispositivo,
                'total'       => $item->quantidade,
                'porcentagem' => porcentagem($item->quantidade, $total)
            ];
        }
        return array_values($relatorio);
    }
}
