<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use App\Classes\ParceiroLoja\TipoLoja;

class SelectModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    public function __construct(
        private ?string $titulo = null,
        private TipoLoja $tipo_loja = new TipoLoja(null)
    ) {
        parent::__construct();
    }

    public function listarDados(): array
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'titulo_interno'])
            ->where($this->pegarWhere())
            ->order('titulo', 'ASC')
            ->read();
        return $this->montarDado($dado);
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[$r->uuid] = !empty($r->titulo_interno) ? $r->titulo_interno : $r->titulo;
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [];
        if ($this->tipo_loja->valido()) {
            $where[] = ['tipo_loja', $this->tipo_loja->numero()];
        }
        $where[] = ['status', 'in', [4, 5]];
        return $where;
    }
}
