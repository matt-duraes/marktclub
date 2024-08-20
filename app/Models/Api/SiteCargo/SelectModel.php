<?php

namespace App\Models\Api\SiteCargo;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use ORM\ORM;

class SelectModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SITE_CARGO;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Request $request
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function listarDados(): array
    {
        return $this->pegarSelect('id', 'titulo', array_merge($this->ormWherePadrao, [['status', 1]]));
    }
}
