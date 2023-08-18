<?php

namespace App\Models\Api\ParceiroIndicacao;

use App\Classes\ParceiroIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class ParceiroIndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PARCEIRO_INDICACAO;
    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public string $mensagem;
    public ?int $idEmpresa;
    public ?int $idUsuario;
    protected array $ormSalvar = ['nome', 'telefone', 'email', 'mensagem', 'status'];
    protected array $ormBuscar = ['nome', 'telefone', 'email', 'mensagem', 'status'];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
        email|Email|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }
}
