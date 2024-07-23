<?php

namespace App\Models\Api\ParceiroSubcategoria;

use ORM\ORM;
use Modules\Botao;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class SelectModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PARCEIRO_SUBCATEGORIA;
    private int $idEmpresa;

    public function __construct(
        private Categoria $categoria,
        private ?string $titulo,
        private Botao $clube
    ) {
        $this->validarEmpresa('empresa');
        parent::__construct();
    }

    public function listarDados()
    {
        return $this->pegarSelect('url', 'titulo', $this->pegarWhere(), titulo: $this->titulo);
    }

    private function pegarWhere(): array
    {
        $where = [
            ['empresa', 'like', '%"' . $this->idEmpresa . '"%']
        ];
        if ($this->categoria->valido()) {
            $where[] = ['categoria', $this->categoria->numero()];
        }
        if($this->pExiste('clube') && $this->clube->valor() == 'sim') {
            $where[] = ['clube', 1];
        }
        return $where;
    }
}
