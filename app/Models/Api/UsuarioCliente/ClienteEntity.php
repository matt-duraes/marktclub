<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\Entity;
use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Telefone;
use Modules\EstadoCivil;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\Situacao;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\UsuarioGrupo\GrupoEntity;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\UsuarioPagamento\PagamentoModel;
use App\Models\Api\UsuarioCliente\Trait\CampoUnicoTrait;

final class ClienteEntity extends Entity
{
    use CampoUnicoTrait;

    protected string $_tabela = TABELA_USUARIO_NOVO;
    protected array $_buscar = [
        'cpf' => 'documento',
        'rg' => 'documento_rg',
        'email' => ['email_trabalho', 'email_pessoal'],
        'telefone_pessoal' => 'telefone_celular',
        'telefone_trabalho' => 'telefone_fixo',
        'genero' => 'sexo',
        'data_nascimento' => 'aniversario',
        'id_admin_empresa' => 'empresa',
        'endereco_estado' => 'uf',
        'endereco_cidade' => 'cidade',
        'trabalho_empresa' => 'trabalho_orgao',
        'senha' => 'salt',
        'origem' => 'lead_origem',
        'lead' => 'usuario_lead',
        'nome', 'siape', 'email_trabalho', 'email_pessoal', 'email_funcional', 'status', 'estado_civil',
        'matricula', 'primeiro_acesso', 'mudar_senha', 'data_criacao', 'data_atualizacao', 'endereco_cep',
        'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao',
        'trabalho_cargo', 'tipo_pagamento', 'trabalho_data_inicio', 'mensagem', 'grupo', 'tipo'
    ];
    protected array $_salvar = [
        'documento' => '->cpf',
        'sexo' => '->genero',
        'telefone_celular' => '->telefone_pessoal',
        'telefone_fixo' => '->telefone_trabalho',
        'aniversario' => '->data_nascimento',
        'uf' => '->endereco_estado',
        'cidade' => '->endereco_cidade',
        'salt' => '->senha',
        'trabalho_orgao' => '->trabalho_empresa',
        'siape', 'nome', 'email_trabalho', 'email_pessoal', 'email_funcional', 'estado_civil', 'mensagem',
        'status', 'matricula', 'primeiro_acesso', 'mudar_senha', 'endereco_cep', 'endereco_logradouro',
        'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'grupo'
    ];
    protected array $_insert = [
        'empresa' => '->idEmpresa',
        'cod', 'tipo'
    ];
    protected string $_validarSalvar = '
        documento|CPF|cpf
        genero|Gênero|valido
        data_nascimento|Data Nascimento|dataDate
        email_trabalho|E-mail de trabalho|email
        email_pessoal|E-mail pessoal|email
        email_funcional|E-mail funcional|email
        telefone_pessoal|Telefone pessoal|telefone
        telefone_trabalho|Telefone de trabalho|telefone
        trabalho_empresa|Empresa que trabalha|valido
        trabalho_cargo|Cargo na empresa|valido
        tipo_pagamento|Tipo de pagamento|valido
        senha|Senha|senha
        status|Status|valido
    ';

    public Cpf $cpf;
    public Email $email;
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;
    public Telefone $telefone_pessoal;
    public Telefone $telefone_trabalho;
    public Senha $senha;
    public Data $data_nascimento;
    public Data $trabalho_data_inicio;
    public TrabalhoEmpresa $trabalho_empresa;
    public TrabalhoCargo $trabalho_cargo;
    public TipoPagamento $tipo_pagamento;
    public Genero $genero;
    public EstadoCivil $estado_civil;
    public int $id_admin_empresa;
    public Botao $primeiro_acesso;
    public Botao $mudar_senha;
    public Botao $lead;
    public Botao $mensagem;
    public Situacao $situacao;
    public Status $status;
    public string $imagem;
    public array $pagamento;
    public string $grupo;
    public Origem $origem;
    public TipoUsuario $tipo;

    public string $contrato_siape;
    private array $campoObrigatorio;
    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null,
        private bool $validarToken = true
    ) {
        parent::__construct();
        if (!$validarToken) {
            return;
        }

        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioCliente\ClienteEntity');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->_wherePadrao = ['empresa', $this->idEmpresa];

        try {
            $Config = new ConfiguracaoEntity();
            $this->campoObrigatorio = $Config->campo_obrigatorio['usuario_cliente'] ?? [];
        } catch (\Throwable) {
            $this->campoObrigatorio = ["cpf", "email", "status"];
        }
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
        $this->matriculaExiste();
        $this->siapeExiste();
        $this->grupoValido();
        $this->tipo = new TipoUsuario('titular');
    }

    private function grupoValido()
    {
        $Grupo = new GrupoEntity();
        if (
            !empty($this->request->grupo) &&
            !$Grupo->existe([
                ['indice', $this->grupo],
                ['id_admin_empresa', $this->idEmpresa]
            ])
        ) {
            mensagemErro('Campo inválido!', 'O grupo informado não é um valor válido.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->cod = uuid();
        $this->mensagem = new Botao('sim');

        $this->validarCamposObrigatorioNoInsert();

        if (!$this->request->existe('status') || empty($this->request->status)) {
            $this->status = new Status('inativo');
        }
    }
    private function validarCamposObrigatorioNoInsert()
    {
        $request = $this->request;
        $campoObrigatorio = $this->campoObrigatorio;
        $emailPessoal = $request->existe('email_pessoal') ? $this->email_pessoal->email() : '';
        $emailTrabalho = $request->existe('email_trabalho') ? $this->email_trabalho->email() : '';

        if (
            in_array('nome', $campoObrigatorio) &&
            (!$request->existe('nome') || $this->nome->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if (
            in_array('cpf', $campoObrigatorio) &&
            (!$request->existe('cpf') || $this->cpf->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (
            in_array('email', $campoObrigatorio) && empty($emailPessoal) && empty($emailTrabalho)
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } else if (
            in_array('status', $campoObrigatorio) &&
            (!$request->existe('status') || $this->status->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } else if (
            in_array('matricula', $campoObrigatorio) &&
            (!$request->existe('matricula') || empty($this->matricula))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } else if (
            in_array('siape', $campoObrigatorio) &&
            (!$request->existe('siape') || empty($this->siape))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA O UPDATE
    |--------------------------------------------------------------------------
    */
    protected function regraUpdate()
    {
        $cpfAtual = $this->prop('documento');
        if (!empty($cpfAtual) && $cpfAtual != $this->cpf->numero()) {
            mensagemErro('Erro!', 'Você não pode mudar o CPF desse usuário.');
        }
        $this->validarCamposObrigatorioNoUpdate();
    }
    private function validarCamposObrigatorioNoUpdate()
    {
        $campoObrigatorio = $this->campoObrigatorio;
        $request = $this->request;
        $emailExiste = $request->existe('email_pessoal') || $request->existe('email_trabalho');

        if (
            in_array('nome', $campoObrigatorio) && $request->existe('nome') && $this->nome->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if (
            in_array('cpf', $campoObrigatorio) && $request->existe('cpf') && $this->cpf->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (
            in_array('email', $campoObrigatorio) &&
            $emailExiste &&
            $this->email_pessoal->vazio() &&
            $this->email_trabalho->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } else if (
            in_array('status', $campoObrigatorio) && $request->existe('status') && !$this->status->valido()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } else if (
            in_array('matricula', $campoObrigatorio) && $request->existe('matricula') && empty($this->matricula)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } else if (
            in_array('siape', $campoObrigatorio) && $request->existe('siape') && empty($this->siape)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA BUSCAR
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->contratoSiape = '';
        if (!$this->trabalho_empresa->vazio() && !empty($this->siape) && $this->idEmpresa == 19) {
            $this->contratoSiape = $this->trabalho_empresa->numero() . $this->siape . '341201';
        }
        $this->imagem = imagemUsuario();

        if ($this->validarToken) {
            $Pagamento = new PagamentoModel();
            $this->pagamento = $Pagamento->buscarPagamento($this->get('id'));
        }
    }

    public function getId()
    {
        return $this->prop('id');
    }

    public function getCpf()
    {
        return $this->prop('documento');
    }
}
