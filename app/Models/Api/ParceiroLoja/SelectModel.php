<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use App\Classes\ParceiroLoja\Tipo;

class SelectModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    public function __construct(
        private ?string $titulo = null,
        private Tipo $tipo = new Tipo(null)
    ) {
        parent::__construct();
    }

    public function listarDados(): array
    {
        return $this
            ->pegarSelect(
                indice: 'uuid',
                valor: 'titulo',
                where: $this->pegarWhere(),
                titulo: $this->titulo
            );
    }

    protected function pegarWhere(): array
    {
        $where = [];
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        $where[] = ['status', 'in', [4, 5]];
        return $where;
    }
}
