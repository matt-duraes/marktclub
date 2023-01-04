<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Status;
use App\Models\Api\AdminEmpresa\EmpresaEntity;

final class DemandaEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_DADO;
    protected array $_insert = [
        'id_admin_empresa', 'id_usuario_equipe', 'titulo', 'tipo', 'status'
    ];
    protected string $_validarSalvar = '
        titulo|Título|obrigatorio|vazio
        tipo|Tipo|vazio|valido
        status|Status|vazio|valido
    ';

    public int $equipe;
    public int $id_admin_empresa;
    public Status $status;
    protected int $id_usuario_equipe;

    /**
     * @param   null|string     $titulo     Título da demanda que deseja salvar
     * @param   null|string     $empresa    UUID da empresa dona da demanda
     * @param   null|Tipo       $tipo       Típo da demanda que deseja salvar
     */
    public function __construct(
        public ?string $titulo = null,
        public ?string $empresa = null,
        public ?Tipo $tipo = null
    ) {
        parent::__construct();
    }

    private function pegarIdEmpresa()
    {
        try {
            $Empresa = new EmpresaEntity();
            $Empresa->id($this->empresa);
            $this->id_admin_empresa = $Empresa->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhuma empresa pelo id enviado.', status: 404);
        }
    }

    protected function regraInsert()
    {
        $this->status = new Status('nova');
        $this->id_usuario_equipe = TOKEN['usuario']->get('id');
        $this->pegarIdEmpresa();
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
