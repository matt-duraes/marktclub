<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

final class PaginaMaisAcessadaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_PAGINA;

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
                'quantidade', 'url'
            ])
            ->where($this->pegarWherePadrao())
            ->order('quantidade')
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
        $dado = [];
        $total = 0;
        foreach ($analytics as $item) {
            $total += $item->quantidade;
            if (!array_key_exists($item->url, $dado)) {
                $dado[$item->url] = object([
                    'url'        => $item->url,
                    'quantidade' => 0
                ]);
            }
            $dado[$item->url]->quantidade += $item->quantidade;
        }

        usort($dado, function ($a, $b) {
            $a = $a->quantidade;
            $b = $b->quantidade;
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? 1 : -1;
        });

        $retorno = [];
        $i = 1;
        foreach ($dado as $r) {
            $retorno[] = [
                'pagina'      => empty($r->url) ? '/' : $r->url,
                'total'       => $r->quantidade,
                'porcentagem' => porcentagem($r->quantidade, $total)
            ];
            if ($i >= 20) {
                break;
            }
            $i++;
        }
        return $retorno;
    }
}
