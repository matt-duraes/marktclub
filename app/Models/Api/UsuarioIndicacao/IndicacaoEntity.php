<?php

namespace App\Models\Api\UsuarioIndicacao;

use Throwable;
use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Helpers\EmailHelper;
use Modules\Nome;

final class IndicacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_INDICACAO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome', 'email', 'telefone',
        'status', 'data_criacao', 'data_atualizacao', 'hash',
        'vinculo'
    ];
    protected array $ormInsert = [
        'id_admin_empresa', 'id_usuario_cliente', 'hash',
        'nome', 'email', 'telefone', 'status'
    ];
    protected array $ormUpdate = [
        'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        telefone|Telefone|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected int $vinculo;
    public Nome $nome;
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

    protected function regraInsert(): void
    {
        $this->validarCampoUnico();
        $this->id_admin_empresa = $this->idEmpresa;
        $this->hash = uuid();
        $this->status = new Status(Status::INDICADO);
        $this->setarUsuarioQueIndicou();
    }

    private function validarCampoUnico()
    {
        $usuarioAtivado = (new OrmHelper(TABELA_USUARIO_INDICACAO))
            ->pegarPrimeiroRegistro([
                ['email', $this->email->email()],
                ['status', (new Status(Status::ATIVADO))->numero()],
                ['id_admin_empresa', $this->idEmpresa]
            ], ['id'], 'object');

        if (!empty($usuarioAtivado)) {
            mensagemErro('Erro!', 'Usuário já cadastrado');
        }
    }

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

    protected function regraPosInsert(): void
    {
        $this->enviarEmail();
    }

    private function enviarEmail(): void
    {
        if (eLocalhost()) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id_admin_empresa', $this->idEmpresa]);

        $link = $Construtor->link_clube;
        $titulo = $Construtor->titulo;

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: 'Cadastro realizado!',
            mensagem: 'Olá <strong>' . $this->nome->primeiroNome() . '</strong>, você foi cadastrado no ' . $titulo . '. Para ativar seu
            cadastro, clique no botão abaixo:',
            assunto: 'Cadastro realizado!',
            botaoTexto: 'Ativar cadastro',
            botaoLink: $link . `/login?hash={$this->hash}&tipo_usuario={$this->status->indice()}`,
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            acao: 'Cadastro de indicado',
            logo: $Construtor->logo_principal,
            cor: $Construtor->cor_principal
        );
        $Email->sendGrid('Cadastro Realizado', $this->nome->nome(), $this->email->email(), deNome: $titulo);
    }

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

    protected function setarUsuarioAtivado(): void
    {
        if ($this->status->indice() !== Status::ATIVADO) {
            return;
        }

        $Usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))
            ->pegarPrimeiroRegistro([
                ['id', $this->vinculo],
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
