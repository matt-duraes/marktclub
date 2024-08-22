<?php

namespace App\Models\Api\SiteLotacao;

use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use ORM\Entity;

class LotacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $slug;
    public string $titulo;
    public Status $status;
    protected string $ormTabela = TABELA_SITE_LOTACAO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'slug', 'titulo', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'slug', 'titulo', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }
}
