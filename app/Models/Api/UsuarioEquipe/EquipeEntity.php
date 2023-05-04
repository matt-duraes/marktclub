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
use Helpers\UploadHelper;
use App\Classes\UsuarioEquipe\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioEquipe\Trait\CampoUnicoTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class EquipeEntity extends Entity
{
    use ValidarEmpresaTrait;
    use CampoUnicoTrait;

    protected string $ormTabela = TABELA_USUARIO_EQUIPE;

    protected array $ormBuscar = [
        'nome' => 'nome_real',
        'cpf' => 'documento_cpf',
        'perfil' => 'nome_perfil',
        'email' => ['email_trabalho', 'email_pessoal'],
        'senha' => 'salt',
        'email_trabalho', 'email_pessoal', 'telefone_pessoal', 'telefone_trabalho', 'status', 'genero',
        'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'data_criacao', 'data_atualizacao',
        'id_admin_empresa', 'permissao', 'imagem_tipo', 'imagem_arquivo', 'imagem_facebook', 'imagem_google',
        'id_facebook', 'id_google', 'marktclub', 'gerente', 'admin'
    ];
    protected array $ormSalvar = [
        'nome_real' => '->nome',
        'documento_cpf' => '->cpf',
        'salt' => '->senha',
        'nome_perfil' => '->perfil',
        'email_trabalho', 'email_pessoal', 'genero', 'telefone_pessoal', 'telefone_trabalho', 'status',
        'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'marktclub', 'permissao', 'admin'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'tipo' => 1
    ];
    protected array $ormUpdate = [
        'imagem_tipo', 'imagem_arquivo', 'imagem_facebook', 'imagem_google', 'id_facebook', 'id_google'
    ];
    protected array $ormRetornoPadrao = ['id', 'nome', 'cpf', 'perfil', 'imagem'];

    protected string $ormValidarSalvar = '
        cpf|CPF|vazio|cpf
        genero|Gênero|valido
        data_nascimento|Data Nascimento|dataDate
        email_trabalho|E-mail de trabalho|vazio|email
        email_pessoal|E-mail pessoal|email
        telefone_pessoal|Telefone pessoal|telefone
        telefone_trabalho|Telefone de trabalho|telefone
        status|Status|vazio|valido
    ';

    public Nome $nome;
    public string $perfil;
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
    public UploadedFile|UploadHelper|string $imagem_arquivo;
    protected int $imagem_tipo;
    protected string $imagem_facebook;
    protected string $imagem_google;
    public Botao $marktclub;
    public Botao $gerente;
    public Botao $admin;
    public string $id_google;
    public string $id_facebook;
    public EmpresaEntity $Empresa;

    private int $idEmpresa;
    public function __construct(
        private bool $validarToken = true
    ) {
        parent::__construct();

        if (!$validarToken) {
            return;
        }
        $this->validarEmpresa();
    }

    protected function getId()
    {
        return $this->prop('id');
    }
    protected function setEmpresa($valor)
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->uuid($valor, mensagem: 'Não foi possível achar uma empresa pelo dado enviado.');
        $this->setarIdEmpresaManual($this->Empresa->get('id'));
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA BUSCAR
    |--------------------------------------------------------------------------
    */
    public function regraPosBuscar()
    {
        $Empresa = new EmpresaEntity();
        $Empresa->id($this->id_admin_empresa, erro: false);
        $this->Empresa = $Empresa;

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
        } elseif ($this->propriedadeExiste('nome') && !$this->nome->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo nome deve conter pelo menos um sobrenome.');
        } elseif ($this->propriedadeExiste('cpf') && empty($this->cpf->cpf())) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif ($this->propriedadeExiste('email_trabalho') && empty($this->email_trabalho->email())) {
            mensagemErro('Campo obrigatório!', 'O campo e-mail de trabalho é obrigatório.');
        } elseif ($this->propriedadeExiste('permissao') && empty($this->permissao)) {
            mensagemErro("Campo obrigatório!", "Você deve marcar as permissões do usuário.");
        } elseif ($this->propriedadeExiste('senha') && !$this->senha->vazio() && !$this->senha->valido()) {
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

        $perfil = $this->nome->primeiroNome();
        $this->perfil = $this->criarPerfilValido(strSlug($perfil, '.'));
    }
    private function criarPerfilValido($perfil, int $numero = 0)
    {
        $perfilFinal = $perfil;
        if ($numero > 0) {
            $perfilFinal .= '.' . $numero;
        }

        if ($this->existe(['nome_perfil', $perfilFinal])) {
            $numero++;
            return $this->criarPerfilValido($perfil, $numero);
        }
        return $perfilFinal;
    }

    protected function regraUpdate()
    {
        if ($this->foiSetado('imagem_facebook')) {
            $this->imagem_tipo = 3;
        } elseif ($this->foiSetado('imagem_google')) {
            $this->imagem_tipo = 2;
        } elseif ($this->imagem_arquivo instanceof UploadedFile) {
            $this->imagem_tipo = 1;
            $this->imagem_arquivo = (new UploadHelper(
                $this->imagem_arquivo,
                diretorio: 'usuario',
                ext: ['png', 'jpg', 'jpeg'],
                nome: $this->id,
                nomeForcar: true,
                mbMaximo: 5
            ))->redimencionar(1000, 1000);
        }

        $this->atualizarPerfilUsuario();

        $cpfAtual = (int)$this->prop('documento_cpf');
        if (validarCpf($cpfAtual) && (int)$this->cpf->numero() != $cpfAtual) {
            mensagemErro('Campo inválido!', 'Você não pode mudar o CPF desse usuário.');
        }
    }
    private function atualizarPerfilUsuario()
    {
        $perfil = $this->perfil;
        $perfilAtual = $this->prop('nome_perfil');
        $id = $this->prop('id');

        if (empty($this->perfil) || $perfilAtual == $perfil) {
            return;
        } elseif (!preg_match('/^[a-z]{1,}[a-z0-9\.]{0,}[a-z0-9]{1,}$/', $perfil)) {
            mensagemErro(
                'Campo inválido!',
                '
                    O perfil deve conter apenas letras minúsculas (a-z), ponto (.) e não pode
                    começar ou terminar com ponto (.) e ter dois pontos (..) seguidos.
                '
            );
        } elseif (
            $this->existe([
                ['id', '!=', $id],
                ['nome_perfil', $this->perfil]
            ])
        ) {
            mensagemErro('Campo inválido!', 'O perfil informado já está em uso por outro usuário.');
        }
    }
}
