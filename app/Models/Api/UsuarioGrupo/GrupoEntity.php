<?php

namespace App\Models\Api\UsuarioGrupo;

use ORM\Entity;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class GrupoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $indice;
    public string $titulo;
    public Status $status;
    public array $empresa;
    protected string $ormTabela = TABELA_USUARIO_GRUPO;
    protected array $ormBuscar = [
        'titulo', 'indice', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'titulo', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'indice', 'titulo', 'status'
    ];
    protected array $ormUpdate = [
        'titulo', 'status'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        status|Status|obrigatorio|vazio
    ';
    protected int $id_admin_empresa;

    public function __construct(
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function setarIndice($titulo)
    {
        $this->indice = strSlug($titulo);
    }

    protected function regraInsert()
    {
        $this->setarIndice($this->titulo);
        $this->validarCampoDuplicado('indice', $this->indice);
    }
}
