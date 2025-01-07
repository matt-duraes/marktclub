<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

final class OsModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_OS;

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
                'quantidade', 'os'
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
            if (array_key_exists($item->os, $relatorio)) {
                $relatorio[$item->os]['total'] += $item->quantidade;
                $relatorio[$item->os]['porcentagem'] = porcentagem(
                    $relatorio[$item->os]['total'],
                    $total
                );
                continue;
            }

            $relatorio[$item->os] = [
                'os'          => $item->os,
                'total'       => $item->quantidade,
                'porcentagem' => porcentagem($item->quantidade, $total)
            ];
        }
        return array_values($relatorio);
    }
}
