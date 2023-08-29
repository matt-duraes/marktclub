<?php

namespace App\Models\Api\ParceiroIndicacao;

use App\Classes\ParceiroIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class ParceiroIndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public string $mensagem;
    protected string $ormTabela = TABELA_PARCEIRO_INDICACAO;
    protected array $ormBuscar = [
        'nome', 'telefone', 'email', 'mensagem', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'nome', 'telefone', 'email', 'mensagem', 'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
        email|Email|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->status = new Status(Status::NOVO);
    }
}
