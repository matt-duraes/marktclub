<?php

namespace App\Models\Api\UsuarioGrupo;

use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use ORM\Entity;

final class GrupoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected array $ormBuscar = [
        'titulo', 'indice', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'titulo', 'indice', 'status'
    ];
    public string $titulo;
    public string $indice;
    public Status $status;
    public array $empresa;
    protected string $ormTabela = TABELA_USUARIO_GRUPO;

    public function __construct(
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }
}
