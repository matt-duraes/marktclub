<?php

namespace App\Models\Api\UsuarioIndicacao;

use Erro\Erro;
use Throwable;
use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class IndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_INDICACAO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome', 'email', 'telefone',
        'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa', 'id_usuario_cliente', 'hash',
        'nome', 'email', 'telefone', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    private int $idEmpresa;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected string $nome;
    public Email $email;
    public Telefone $telefone;
    public array $quem_indicou = [];
    public array $usuario_ativo = [];
    public string $usuario;
    public Status $status;
    public string $hash;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->hash = uuid();
        $this->status = new Status(Status::INDICADO);
        $this->setarUsuarioQueIndicou();
    }

    /**
     * @throws Excecao
     */
    private function setarUsuarioQueIndicou(): void
    {
        try {
            $Usuario = new ClienteEntity();
            $Usuario->uuid($this->usuario);
            $this->id_usuario_cliente = $Usuario->get('id');
        } catch (Throwable) {
            mensagemErro('Erro!', 'Usuário enviado não foi encontrado');
        }
    }

    /**
     * @throws Excecao|Erro
     */
    protected function regraPosBuscar(): void
    {
        $this->setarQuemIndicou();
        $this->setarUsuarioAtivado();
    }

    protected function setarQuemIndicou(): void
    {
        $Usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))
            ->pegarPrimeiroRegistro([
                ['id', $this->id_usuario_cliente],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ], ['cod', 'nome', 'documento', 'email_pessoal'], 'object');

        if (empty($Usuario)) {
            return;
        }

        $this->quem_indicou = [
            'id'    => $Usuario->cod,
            'nome'  => $Usuario->nome,
            'cpf'   => (new Cpf($Usuario->documento))->cpf(),
            'email' => (new Email($Usuario->email_pessoal))->email()
        ];
    }

    /**
     * @throws Excecao|Erro
     */
    protected function setarUsuarioAtivado(): void
    {
        if ($this->status->indice() !== Status::ATIVADO) {
            return;
        }

        $Usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))
            ->pegarPrimeiroRegistro([
                ['id_usuario_indicacao', $this->prop('id')],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ], ['cod', 'nome', 'documento', 'email_pessoal'], 'object');

        if (empty($Usuario)) {
            return;
        }

        $this->usuario_ativo = [
            'id'    => $Usuario->cod,
            'nome'  => $Usuario->nome,
            'cpf'   => (new Cpf($Usuario->documento))->cpf(),
            'email' => (new Email($Usuario->email_pessoal))->email()
        ];
    }
}
