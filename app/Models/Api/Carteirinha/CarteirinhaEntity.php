<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use ORM\Entity;

class CarteirinhaEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $bg_frente;
    public string $bg_fundo;
    public Botao $nome;
    public Botao $cpf;
    public Botao $matricula;
    public Botao $data_nascimento;
    public Botao $estado;
    public Status $status;
    public string $empresa;
    protected string $ormTabela = TABELA_CARTEIRINHA;
    protected array $ormBuscar = [
        'id_admin_empresa', 'bg_frente', 'bg_fundo', 'nome', 'cpf', 'matricula', 'data_nascimento',
        'status', 'data_criacao', 'data_atualizacao', 'titulo', 'estado'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'bg_frente', 'bg_fundo', 'nome', 'cpf', 'matricula', 'data_nascimento',
        'status', 'titulo', 'estado'
    ];
    protected string $ormValidarSalvar = '
        id_admin_empresa|Empresa|obrigatorio|vazio
        bg_frente|Imagem frente|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;
    private OrmHelper $OrmEmpresa;

    public function __construct()
    {
        $this->OrmEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        if ($this->propriedadeExiste('empresa') && !empty($this->empresa)) {
            $this->id_admin_empresa = $this->OrmEmpresa->pegarIdPeloUuid($this->empresa);
        }

        if ($this->existe(['id_admin_empresa', $this->id_admin_empresa])) {
            mensagemErro('Empresa duplicada', 'Já existe uma carteirinha cadastrada!');
        }
    }

    /**
     * @throws Excecao
     */
    public function regraUpdate(): void
    {
        if ($this->propriedadeExiste('empresa') && !empty($this->empresa)) {
            $this->empresa = $this->OrmEmpresa->pegarIdPeloUuid($this->empresa);
        }
        
        if ($this->existe(['id_admin_empresa', $this->empresa]) && $this->id_admin_empresa != $this->empresa) {
            mensagemErro('Empresa duplicada', 'Já existe uma carteirinha cadastrada!');
        }
    }

    public function regraPosBuscar(): void
    {
        $this->empresa = $this->OrmEmpresa->pegarUuidPeloId($this->id_admin_empresa);
    }
}
