<?php

namespace App\Models\Api\Publicidade;

use App\Classes\Publicidade\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use ORM\Entity;

class PublicidadeEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICIDADE;
    protected array $ormBuscar = [
        'titulo', 'imagem', 'target', 'link', 'tipo'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => 'idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'imagem', 'target', 'link', 'tipo'
    ];
    protected ?int $idEmpresa;
    protected string $titulo;
    protected string $imagem;
    protected string $link;
    protected string $target;
    protected string $parceiro;
    protected string $ordem;
    protected Tipo $tipo;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }
}
