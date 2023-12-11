<?php

namespace App\Models\Api\UsuarioDependente;

use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Helpers\EmailHelper;
use SendGrid\Mail\TypeException;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

final class DependenteEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Nome $nome;
    public Cpf $cpf;
    public Email $email;
    public string $usuario;
    public Status $status;
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    protected array $ormInsert = [
        'cod', 'nome', 'tipo', 'titular', 'data_email', 'status', 'cpf',
        'email_pessoal' => '->email',
        'empresa'       => '->idEmpresa'
    ];
    protected array $ormBuscar = [
        'id', 'nome', 'cpf', 'status',
        'email' => 'email_pessoal'
    ];
    protected int $titular;
    protected string $cod;
    protected TipoUsuario $tipo;
    protected Data $data_email;
    private int $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioDependente\DependenteEntity.');
        }
        $this->validarEmpresa('empresa');
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->pegarTitular();
        $this->validarNumeroDependentes();
        $this->validarCampos();
        $this->cpfJaExiste();
        $this->emailJaExiste();

        $this->cod = uuid();
        $this->tipo = new TipoUsuario(TipoUsuario::DEPENDENTE);
        $this->data_email = new Data(hoje());
        $this->status = new Status(Status::INATIVO);
    }

    /**
     * @throws Excecao
     */
    private function pegarTitular(): void
    {
        if (empty($this->usuario)) {
            mensagemErro(
                'Campo obrigatório!',
                'Você deve passar o titular do dependente para salvar.'
            );
        }

        $usuario = $this
            ->campo(['id', 'tipo'])
            ->where([
                ['cod', $this->usuario],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ])
            ->primeiro();

        if (empty($usuario)) {
            mensagemErro(
                'Erro!',
                'Não foi possível encontrar o usuário para vincular o dependente.'
            );
        } elseif ((new TipoUsuario($usuario->tipo))->indice() == TipoUsuario::DEPENDENTE) {
            mensagemErro(
                'Erro!',
                'Um dependente não pode adicionar outros dependentes.'
            );
        }
        $this->titular = $usuario->id;
    }

    /**
     * @throws Excecao
     */
    private function validarNumeroDependentes(): void
    {
        if (
            $this->contar([
                ['empresa', $this->idEmpresa],
                ['tipo', 2],
                ['titular', $this->titular],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ]) >= 5
        ) {
            mensagemErro('Erro!', 'Cada usuário só pode ter 5 dependentes.');
        }
    }

    /**
     * @throws Excecao
     */
    private function validarCampos(): void
    {
        if ($this->nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo Nome é obrigatório.');
        } elseif (!$this->nome->valido()) {
            mensagemErro('Campo inválido!', 'Digite o nome com pelo menos um sobrenome.');
        } elseif ($this->cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O CPF informado não é válido.');
        } elseif ($this->email->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail é obrigatório.');
        } elseif (!$this->email->valido()) {
            mensagemErro('Campo inválido!', 'O e-mail informado não é válido.');
        }
    }

    /**
     * @throws Excecao
     */
    private function cpfJaExiste(): void
    {
        if (
            $this->existe([
                ['empresa', $this->idEmpresa],
                ['documento', $this->cpf->numero()]
            ])
        ) {
            mensagemErro('CPF duplicado!', 'O CPF informado já está em uso por outro usuário.');
        }
    }

    /**
     * @throws Excecao
     */
    private function emailJaExiste(): void
    {
        if (
            $this->existe([
                ['empresa', $this->idEmpresa],
                [
                    'OR',
                    ['email_pessoal', $this->email->email()],
                    ['email_trabalho', $this->email->email()],
                    ['email_funcional', $this->email->email()],
                ]
            ])
        ) {
            mensagemErro('E-mail duplicado!', 'O e-mail informado já está em uso por outro usuário.');
        }
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosInsert(): void
    {
        $this->enviarEmail();
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    public function enviarEmail(): void
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
            botaoLink: $link . '/login#ativar',
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            acao: 'Cadastro de dependente',
            logo: $Construtor->logo_principal,
            cor: $Construtor->cor_principal
        );
        $Email->sendGrid('Cadastro Realizado', $this->nome->nome(), $this->email->email(), deNome: $titulo);
    }
}
