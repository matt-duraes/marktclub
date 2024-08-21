<?php

namespace App\Models\Api\SiteLotacao;

use ORM\ORM;
use Erro\Excecao;
use Http\Request;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class SelectModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SITE_LOTACAO;

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
        return $this->pegarSelect('id', 'titulo', $this->ormWherePadrao);
    }
}
