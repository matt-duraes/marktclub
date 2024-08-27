<?php

namespace App\Models\Api\SiteLotacao;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use ORM\ORM;

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
        return $this->pegarSelect('id', 'titulo', [
            ['id_admin_empresa', $this->idEmpresa],
            ['status', 1]
        ]);
    }
}
