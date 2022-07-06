<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Telefone;
use App\Classes\UsuarioEquipe\Status;
use App\Models\Api\UsuarioEquipe\Trait\CampoUnicoTrait;

final class EquipeEntity extends Entity
{
    use CampoUnicoTrait;

    protected string $_tabela = TABELA_USUARIO_EQUIPE;

    protected array $_buscar = [
        'nome' => 'nome_real',
        'cpf' => 'documento_cpf',
        'email' => ['email_trabalho', 'email_pessoal'],
        'senha' => 'salt',
        'email_trabalho', 'email_pessoal', 'telefone_pessoal', 'telefone_trabalho', 'status', 'genero',
        'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'data_criacao', 'data_atualizacao',
        'id_admin_empresa', 'permissao', 'imagem_tipo', 'imagem_arquivo', 'imagem_facebook', 'imagem_google'
    ];
    protected array $_salvar = [
        'nome_real' => '->nome',
        'documento_cpf' => '->cpf',
        'salt' => '->senha',
        'email_trabalho', 'email_pessoal', 'genero', 'telefone_pessoal', 'telefone_trabalho', 'status',
        'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'permissao'
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

    public Nome $nome;
    public Cpf $cpf;
    public Senha $senha;
    public Email $email;
    public Botao $primeiro_acesso;
    public array $permissao;
    public int $id_admin_empresa;
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Telefone $telefone_pessoal;
    public Telefone $telefone_trabalho;
    public Data $data_nascimento;
    public Genero $genero;
    public Botao $mudar_senha;
    public Status $status;
    public string $imagem;

    private int $idEmpresa;
    public function __construct(
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

    protected function getId()
    {
        return $this->prop('id');
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA BUSCAR
    |--------------------------------------------------------------------------
    */
    public function regraPosBuscar()
    {
        $this->imagem = imagemUsuario(
            $this->imagem_tipo,
            $this->imagem_arquivo,
            $this->imagem_facebook,
            $this->imagem_google
        );
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

        if ($this->propriedadeExiste('nome') && $this->nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if ($this->propriedadeExiste('nome') && !$this->nome->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo nome deve conter pelo menos um sobrenome.');
        } else if ($this->propriedadeExiste('cpf') && empty($this->cpf->cpf())) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if ($this->propriedadeExiste('email_trabalho') && empty($this->email_trabalho->email())) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail de trabalho é obrigatório.');
        } else if ($this->propriedadeExiste('permissao') && empty($this->permissao)) {
            mensagemErro("Campo obrigatório!", "Você deve marcar as permissões do usuário.");
        } else if ($this->propriedadeExiste('senha') && !$this->senha->vazio() && !$this->senha->valido()) {
            mensagemErro('Senha inválida!', $this->senha->mensagem());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        if (!$this->propriedadeExiste('status')) {
            $this->status = new Status('inativo');
        }
    }
}
