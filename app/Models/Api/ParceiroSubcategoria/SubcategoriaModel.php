<?php

namespace App\Models\Api\ParceiroSubcategoria;

use App\Classes\ParceiroLoja\Categoria;
use ORM\ORM;
use Http\Request;

final class SubcategoriaModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_SUBCATEGORIA;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
    }

    public function listarDado(): array
    {
        $dado = $this
            ->campo(['id', 'categoria', 'titulo', 'url'])
            ->read();

        if (!empty($dado)) {
            $dado = $this->montarDado($dado);
        }

        return $dado;
    }

    private function montarDado($dado): array
    {
        if (empty($dado)) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $categoria = new Categoria($r->categoria);
            $retorno[$categoria->nome()][$r->url] = $r->titulo;
        }
        return $retorno;
    }
}
