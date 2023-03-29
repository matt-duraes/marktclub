<?php

namespace App\Models\Api\UsuarioDependente;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Helpers\EmailHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

final class DependenteEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_USUARIO_CLIENTE;
    protected array $_insert = [
        'cod', 'nome', 'tipo', 'titular', 'data_email', 'status',
        'documento' => '->cpf',
        'email_pessoal' => '->email',
        'empresa' => '->idEmpresa'
    ];

    public Nome $nome;
    public Cpf $cpf;
    public Email $email;
    public string $usuario;
    protected int $titular;
    protected string $cod;
    protected TipoUsuario $tipo;
    protected Data $data_email;
    protected Status $status;

    private int $idEmpresa;

    public function __construct()
    {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioDependente\DependenteEntity.');
        }

        $this->validarEmpresa('empresa');
    }

    protected function regraPosInsert()
    {
        if (eLocalhost()) {
            return;
        }
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['empresa', $this->idEmpresa]);

        $link = $Construtor->link_clube;
        $titulo = $Construtor->titulo;

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: 'Cadastro realizado!',
            assunto: 'Cadastro realizado!',
            mensagem: 'Olá <strong>' . $this->nome->primeiroNome() . '</strong>, você foi cadastrado no ' . $titulo . '. Para ativar seu
            cadastro, clique no botão abaixo:',
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            botaoTexto: 'Ativar cadastro',
            botaoLink: $link . '/login/ativar',
            logo: $Construtor->logo,
            acao: 'Cadastro de dependente',
            cor: $Construtor->cor
        );
        $Email->sendGrid('Cadastro Realizado', $this->nome->nome(), $this->email->email(), deNome: $titulo);
    }

    protected function regraInsert()
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

    private function pegarTitular()
    {
        $Cliente = new ClienteEntity();
        $Cliente->buscar([
            ['cod', $this->usuario],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if (empty($Cliente->id)) {
            mensagemErro('Erro!', 'Não foi possível encontrar o usuário para vincular o dependente.');
        } else if ($Cliente->tipo->indice() == TipoUsuario::DEPENDENTE) {
            mensagemErro('Erro!', 'Um dependente não pode adicionar outros dependentes.');
        }

        $this->titular = $Cliente->get('id');
    }

    private function validarNumeroDependentes()
    {
        if ($this->contar([
            ['empresa', $this->idEmpresa],
            ['tipo', 2],
            ['titular', $this->titular],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]) >= 5) {
            mensagemErro('Erro!', 'Cada usuário só pode ter 5 dependentes.');
        }
    }

    private function validarCampos()
    {
        if ($this->nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo Nome é obrigatório.');
        } else if (!$this->nome->valido()) {
            mensagemErro('Campo inválido!', 'Digite o nome com pelo menos um sobrenome.');
        } else if ($this->cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (!$this->cpf->valido()) {
            mensagemErro('Campo inválido!', 'O CPF informado não é válido.');
        } else if ($this->email->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail é obrigatório.');
        } else if (!$this->email->valido()) {
            mensagemErro('Campo inválido!', 'O e-mail informado não é válido.');
        }
    }
    private function cpfJaExiste()
    {
        if ($this->existe([
            ['empresa', $this->idEmpresa],
            ['documento', $this->cpf->numero()]
        ])) {
            mensagemErro('CPF duplicado!', 'O CPF informado já está em uso por outro usuário.');
        }
    }
    private function emailJaExiste()
    {
        if ($this->existe([
            ['empresa', $this->idEmpresa],
            [
                'OR',
                ['email_pessoal', $this->email->email()],
                ['email_trabalho', $this->email->email()],
                ['email_funcional', $this->email->email()],
            ]
        ])) {
            mensagemErro('E-mail duplicado!', 'O e-mail informado já está em uso por outro usuário.');
        }
    }
}
