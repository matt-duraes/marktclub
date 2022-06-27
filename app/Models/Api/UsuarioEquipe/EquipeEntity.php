<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\Entity;
use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Telefone;
use App\Classes\UsuarioEquipe\Status;
use App\Models\Api\UsuarioEquipe\Trait\CampoUnicoTrait;

final class EquipeEntity extends Entity
{
    use CampoUnicoTrait;

    /*
    |--------------------------------------------------------------------------
    | RETORNO DOS DADOS
    |--------------------------------------------------------------------------
    */
    public function retorno()
    {
        return [
            'id' => $this->id,
            'nome' => strNull($this->nome),
            'cpf' => $this->cpf->numero(),
            'email_trabalho' => $this->email_trabalho->email(),
            'email_pessoal' => $this->email_pessoal->email(),
            'telefone_trabalho' => $this->telefone_trabalho->numero(),
            'telefone_pessoal' => $this->telefone_pessoal->numero(),
            'genero' => $this->genero->genero(),
            'data_nascimento' => $this->data_nascimento->date(),
            'primeiro_acesso' => $this->primeiro_acesso,
            'mudar_senha' => $this->mudar_senha,
            'status' => $this->status->indice(),
            'permissao' => $this->permissao
        ];
    }

    protected function getId()
    {
        return $this->prop('id');
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS DE SALVAR
    |--------------------------------------------------------------------------
    */
    protected function regraSalvar()
    {
        $this->cpfExiste();
        $this->emailTrabalhoExiste();
        $this->emailPessoalExiste();

        if ($this->propriedadeExiste('nome') && empty($this->nome)) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if ($this->propriedadeExiste('cpf') && empty($this->cpf->cpf())) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if ($this->propriedadeExiste('email_trabalho') && empty($this->email_trabalho->email())) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail de trabalho é obrigatório.');
        }

        if ($this->propriedadeExiste('senha') && !$this->senha->vazio() && !$this->senha->valido()) {
            mensagemErro('Senha inválida!', $this->senha->mensagem());
        }

        if ($this->propriedadeExiste('nome')) {
            $this->nome = strCaixaAltaAlta($this->nome);
        }
        if ($this->propriedadeExiste('primeiro_acesso')) {
            $this->primeiro_acesso = $this->primeiro_acesso == 1 ? true : false;
        }
        if ($this->propriedadeExiste('mudar_senha')) {
            $this->mudar_senha = $this->mudar_senha == 1 ? true : false;
        }
        if ($this->request->existe('status')) {
            $this->status = new Status($this->status);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        if (!$this->propriedadeExiste('permissao') || empty($this->permissao)) {
            mensagemErro("Campo obrigatório!", "Você deve marcar as permissões do usuário.");
        }
        if (!$this->propriedadeExiste('status')) {
            $this->status = 2;
        }
    }

    protected function regraUpdate()
    {
        if ($this->propriedadeExiste('permissao') && empty($this->permissao)) {
            mensagemErro("Campo obrigatório!", "Você deve marcar as permissões do usuário.");
        }
    }

    public function getImagem()
    {
        return 'https://arquivo.marktclub.com.br/usuario/padrao_preto.png';
    }

    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR E PROPRIEDADES DA CLASSE
    |--------------------------------------------------------------------------
    |
    | Construtor e propriedades para criação da Entity
    |
    */
    protected string $_tabela = TABELA_USUARIO_EQUIPE;

    protected array $_buscar = [
        'nome' => 'nome_real',
        'cpf' => 'documento_cpf',
        'email' => ['email_trabalho', 'email_pessoal'],
        'email_trabalho',
        'email_pessoal',
        'telefone_pessoal',
        'telefone_trabalho',
        'status',
        'genero',
        'data_nascimento',
        'primeiro_acesso',
        'mudar_senha',
        'senha' => 'salt',
        'data_criacao',
        'data_atualizacao',
        'id_admin_empresa',
        'permissao'
    ];
    protected array $_salvar = [
        'nome_real' => '->nome',
        'documento_cpf' => '->cpf',
        'email_trabalho',
        'email_pessoal',
        'genero',
        'telefone_pessoal',
        'telefone_trabalho',
        'salt' => '->senha',
        'status',
        'data_nascimento',
        'primeiro_acesso',
        'mudar_senha',
        'permissao'
    ];
    protected array $_insert = [
        'id_admin_empresa' => '->idEmpresa',
        'tipo' => 1
    ];

    protected string $_validarSalvar = '
        documento_cpf|CPF|cpf
        genero|Gênero|valido
        data_nascimento|Data Nascimento|dataDate
        email_trabalho|E-mail de trabalho|email
        email_pessoal|E-mail pessoal|email
        telefone_pessoal|Telefone pessoal|telefone
        telefone_trabalho|Telefone de trabalho|telefone
        status|Status|valido
    ';

    public Cpf $cpf;
    public Senha $senha;
    public Email $email;
    public bool $primeiro_acesso;
    public array $permissao;
    public int $id_admin_empresa;
    protected Email $email_trabalho;
    protected Email $email_pessoal;
    protected Telefone $telefone_pessoal;
    protected Telefone $telefone_trabalho;
    protected Data $data_nascimento;
    protected Genero $genero;
    protected bool $mudar_senha;
    protected Status $status;

    private int $idEmpresa;
    public function __construct(
        private ?Request $request = null,
        private bool $validarToken = true
    ) {
        parent::__construct();

        if ($validarToken && !defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioEquipe\EquipeEntity');
        } else if ($validarToken && defined('TOKEN')) {
            $this->idEmpresa = TOKEN['empresa']->get('id');
            $this->_wherePadrao = ['id_admin_empresa', $this->idEmpresa];
        }
    }
}
