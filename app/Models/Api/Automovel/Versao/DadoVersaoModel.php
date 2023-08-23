<?php

namespace App\Models\Api\Automovel\Versao;

use ORM\ORM;

final class DadoVersaoModel extends ORM
{
    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;
    public array $automovel = [];

    public function __construct(
        private int $versao
    ) {
        parent::__construct();
        $this->buscarDado();
    }

    private function buscarDado()
    {
        $dado = $this
            ->campo(['titulo', 'valor_por', 'cor'])
            ->where(['id', $this->versao])
            ->tabela(TABELA_AUTOMOVEL_MODELO)
            ->join('id', 'id_automovel_modelo')
            ->campo(['titulo'], as: 'modelo')
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja', tabela: TABELA_AUTOMOVEL_MODELO)
            ->campo(['titulo'], as: 'parceiro')
            ->primeiro();
        $this->montarAutomovel($dado);
    }

    private function montarAutomovel($dado)
    {
        if (!$dado) {
            return;
        }
        $this->automovel = [
            'parceiro' => $dado->parceiro_titulo,
            'modelo'   => $dado->modelo_titulo,
            'versao'   => $dado->titulo,
            'cor'      => $dado->cor,
            'valor'    => $dado->valor_por
        ];
    }
}
