<?php

namespace App\Models\Api\UsuarioGrupo;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class SelectModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_USUARIO_GRUPO;
    private int $idEmpresa;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->setarWherePadrao(['status', 1]);
    }

    public function listarDados(): array
    {
        return $this->pegarSelect(
            indice: 'indice',
            valor: 'titulo',
            where: $this->_wherePadrao,
            titulo: $this->request->titulo
        );
    }
}
