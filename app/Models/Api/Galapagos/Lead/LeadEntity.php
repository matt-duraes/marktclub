<?php

namespace App\Models\Api\Galapagos\Lead;

use ORM\Entity;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\DataHora;
use Modules\Telefone;
use App\Classes\Galapagos\Lead\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class LeadEntity extends Entity
{
    use ValidarEmpresaTrait;
    protected string $ormTabela = TABELA_GALAPAGOS_LEAD;
    protected array $ormInsert = [
        'data_termo', 'nome', 'email', 'celular', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    protected array $ormBuscar = [
        'nome', 'email', 'celular'
    ];
    protected string $ormValidarInsert = '
        termo|!Você deve aceitar os termo|obrigatorio|vazio|valido
        nome|Nome|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        celular|Celular|obrigatorio|vazio|valido
    ';
    protected string $ormValidarUpdate = '
        status|Status|obrigatorio|vazio|valido
    ';

    protected Status $status;
    protected DataHora $data_termo;
    protected int $id_admin_empresa;

    public function __construct(
        public Botao $termo,
        public Nome $nome,
        public Email $email,
        public Telefone $celular
    )
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraInsert()
    {
        $this->data_termo = new DataHora(agora());
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::FALHA);
    }

    /**
     * Atualiza o status do usuário. Obs: Não salva automáticamente
     *
     * @param  Status $status Novo status
     * @return void
     */
    public function status(Status $status): void
    {
        $this->status = $status;
    }
}
