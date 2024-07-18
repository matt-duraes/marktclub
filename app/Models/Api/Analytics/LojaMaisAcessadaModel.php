<?php

namespace App\Models\Api\Analytics;

use Helpers\OrmHelper;
use ORM\ORM;
use Modules\Data;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Classes\ParceiroLoja\TipoEstabelecimento;

final class LojaMaisAcessadaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_LOJA;

    public function __construct(
        protected Data $de,
        protected Data $ate,
        protected TipoEstabelecimento $estabelecimento,
        private array|string|null $Empresa = null,
        private array|string|null $parceiro = null
    ) {
        parent::__construct();
    }

    public function listarDado(): array
    {
        $lista = $this
            ->campo(['quantidade', 'parceiro_nome', 'id_parceiro_loja'])
            ->where($this->pegarWhere(), false)
            ->order('quantidade', 'DESC')
            ->read();

        return $this->montarDado($lista);
    }

    private function pegarWhere()
    {
        $where = $this->pegarWherePadrao();

        if ($this->estabelecimento->valido()) {
            $where[] = ['parceiro_estabelecimento', $this->estabelecimento->numero()];
        }

        $whereParceiro = $this->pegarWhereParceiro();
        if (!empty($whereParceiro)) {
            $where[] = $whereParceiro;
        }

        return $where;
    }

    private function pegarWhereParceiro()
    {
        if (empty($this->parceiro)) {
            return;
        }

        if (!is_array($this->parceiro)) {
            $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
            $parceiroId = $ormHelper->pegarIdPeloUuid($this->parceiro);
            return ['id_parceiro_loja', $parceiroId];
        }

        $parceiroUuid = $this->parceiro;
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);

        $parceiroId = [];
        foreach ($parceiroUuid as $e) {
            $parceiroId[] = $ormHelper->pegarIdPeloUuid($e);
        }

        return ['id_parceiro_loja', 'in', $parceiroId];
    }

    private function montarDado($lista)
    {
        $dado = [];
        $total = 0;
        foreach ($lista as $r) {
            $total += $r->quantidade;
            if (!array_key_exists($r->id_parceiro_loja, $dado)) {
                $dado[$r->id_parceiro_loja] = object([
                    'parceiro_nome' => $r->parceiro_nome,
                    'quantidade'    => 0,
                ]);
            }
            $dado[$r->id_parceiro_loja]->quantidade += $r->quantidade;
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
