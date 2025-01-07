<?php

namespace App\Models\Api\Analytics;

use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Models\Api\Analytics\Trait\WhereTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use ORM\ORM;

final class LojaMaisAcessadaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_LOJA;

    /**
     * @param Data                $dataInicial
     * @param Data                $dataFinal
     * @param array|string|null   $empresa
     * @param array|string|null   $subempresa
     * @param array|string|null   $parceiro
     * @param TipoEstabelecimento $tipoEstabelecimento
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Data $dataInicial = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly array|string|null $empresa = null,
        private readonly array|string|null $subempresa = null,
        private readonly array|string|null $parceiro = null,
        private readonly TipoEstabelecimento $tipoEstabelecimento = new TipoEstabelecimento()
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
        if (!$this->tipoEstabelecimento->vazio() && !$this->tipoEstabelecimento->valido()) {
            mensagemErro('Campo inválido!', 'O tipo de estabelecimento da busca não é válido.');
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
                'id_parceiro_loja', 'parceiro_nome', 'quantidade'
            ])
            ->where($this->pegarWhere(), false)
            ->order('quantidade')
            ->read();
        return $this->montarRelatorio($analytics);
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhere(): array
    {
        $where = $this->pegarWherePadrao();
        if ($this->tipoEstabelecimento->valido()) {
            $where[] = ['parceiro_estabelecimento', $this->tipoEstabelecimento->numero()];
        }

        $whereParceiro = $this->pegarWhereParceiro();
        if (!empty($whereParceiro)) {
            $where[] = $whereParceiro;
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
     */
    private function montarRelatorio(array $analytics): array
    {
        $dado = [];
        $total = 0;
        foreach ($analytics as $item) {
            $total += $item->quantidade;
            if (!array_key_exists($item->id_parceiro_loja, $dado)) {
                $dado[$item->id_parceiro_loja] = object([
                    'parceiro_nome' => $item->parceiro_nome,
                    'quantidade'    => 0
                ]);
            }
            $dado[$item->id_parceiro_loja]->quantidade += $item->quantidade;
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
                'loja'        => $r->parceiro_nome,
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
