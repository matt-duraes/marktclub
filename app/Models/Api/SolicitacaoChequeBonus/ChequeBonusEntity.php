<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\Solicitacao\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\UsuarioCliente\GrauParentesco;
use App\Models\Api\UsuarioCliente\DadoBaseModel;
use App\Models\Api\Automovel\Versao\DadoVersaoModel;

final class ChequeBonusEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string|array $automovel;
    public TipoUsuario $tipo_usuario;
    public Nome $nome;
    public Email $email_pessoal;
    public Telefone $telefone_celular;
    public EstadoCivil $estado_civil;
    public string $rg;
    public Data $data_nascimento;
    public EnderecoCep $endereco_cep;
    public string $endereco_logradouro;
    public string $endereco_numero;
    public string $endereco_complemento;
    public string $endereco_bairro;
    public string $endereco_cidade;
    public EnderecoEstado $endereco_estado;
    public Nome $dependente_nome;
    public Email $dependente_email_pessoal;
    public string $dependente_rg;
    public Cpf $dependente_cpf;
    public GrauParentesco $dependente_grau_parentesco;
    public Data $dependente_data_nascimento;
    public Status $status;
    public array $dependente = [];
    public array $usuario = [];
    public Data $data_termo;
    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'id_admin_empresa', 'id_automovel_versao', 'tipo_usuario', 'nome',
        'estado_civil', 'rg', 'data_nascimento', 'endereco_cep', 'endereco_logradouro', 'data_termo',
        'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'endereco_cidade',
        'endereco_estado', 'dependente_nome', 'dependente_email_pessoal', 'dependente_rg',
        'dependente_cpf', 'dependente_grau_parentesco', 'dependente_data_nascimento',
        'status', 'email_pessoal', 'telefone_celular', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'id_automovel_versao', 'tipo_usuario', 'nome',
        'estado_civil', 'rg', 'data_nascimento', 'endereco_cep', 'endereco_logradouro',
        'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'endereco_cidade',
        'endereco_estado', 'dependente_nome', 'dependente_email_pessoal', 'dependente_rg',
        'dependente_cpf', 'dependente_grau_parentesco', 'dependente_data_nascimento',
        'email_pessoal', 'telefone_celular', 'data_termo'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarInsert = '
        data_termo|Termo de aceite|obrigatorio|vazio|valido
        tipo_usuario|Tipo de usuário|obrigatorio|vazio|valido
        nome|Nome|obrigatorio|vazio|valido
        email_pessoal|E-mail|obrigatorio|vazio|valido
        telefone_celular|Telefone|obrigatorio|vazio|valido
        estado_civil|Estado Civil|obrigatorio|vazio|valido
        rg|RG|obrigatorio|vazio
        data_nascimento|Data de nascimento|obrigatorio|vazio|valido
        endereco_cep|CEP|obrigatorio|vazio|valido
        endereco_logradouro|Logradouro|obrigatorio|vazio
        endereco_bairro|Bairro|obrigatorio|vazio
        endereco_cidade|Cidade|obrigatorio|vazio
        endereco_estado|Estado|obrigatorio|vazio|valido
    ';
    protected string $ormValidarSalvar = '
        status|Status|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected int $id_automovel_versao;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        if (empty($this->automovel)) {
            mensagemErro('Campo obrigatório!', 'O campo automovel é obrigatório.');
        } elseif (!validarUuid($this->automovel, false)) {
            mensagemErro('Campo obrigatório!', 'O campo automovel não é um código válido.');
        } elseif (!$this->propriedadeExiste('tipo_usuario')) {
            mensagemErro('Campo obrigatório!', 'O campo tipo de usuário é obrigatório.');
        } elseif ($this->data_termo->date() != hoje()) {
            mensagemErro('Campo inválido!', 'A data do termo está inválida.');
        } elseif ($this->data_nascimento->date() >= hoje()) {
            mensagemErro('Campo inválido!', 'A data de nascimento está inválida.');
        }
        $this->id_automovel_versao = (new OrmHelper(TABELA_AUTOMOVEL_VERSAO))->pegarIdPeloUuid(
            $this->automovel,
            'Não foi encontrado nenhum veículo pelo código enviado.'
        );
        $this->status = new Status(Status::NOVO);
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        if ($this->tipo_usuario->indice() == TipoUsuario::DEPENDENTE) {
            $this->ormValidarSalvar .= '
                dependente_nome|Nome do dependente|obrigatorio|vazio|valido|valido
                dependente_email_pessoal|E-mail do dependente|obrigatorio|vazio|valido|valido
                dependente_rg|RG do dependente|obrigatorio|vazio
                dependente_cpf|CPF do dependente|obrigatorio|vazio
                dependente_grau_parentesco|Grau de parêntesco do dependente|obrigatorio|vazio
                dependente_data_nascimento|Data de nascimento do dependente|obrigatorio|vazio
            ';

            if ($this->dependente_data_nascimento->date() > hoje()) {
                mensagemErro('Campo inválido!', 'A data de nascimento do dependente está inválida.');
            }
        }
    }

    protected function regraPosBuscar(): void
    {
        if ($this->tipo_usuario->indice() == TipoUsuario::DEPENDENTE) {
            $this->montarDependente();
        }
        $this->buscarAutomovel();
        $this->buscarUsuario();
    }

    private function montarDependente(): void
    {
        $this->dependente = [
            'nome'            => $this->dependente_nome->nome(),
            'email_pessoal'   => $this->dependente_email_pessoal->email(),
            'rg'              => $this->dependente_rg,
            'cpf'             => $this->dependente_cpf->cpf(),
            'grau_parentesco' => $this->dependente_grau_parentesco->indice(),
            'data_nascimento' => $this->dependente_data_nascimento->date()
        ];
    }

    private function buscarAutomovel(): void
    {
        $Automovel = new DadoVersaoModel($this->id_automovel_versao);
        $this->automovel = $Automovel->automovel;
    }

    private function buscarUsuario(): void
    {
        $Usuario = new DadoBaseModel($this->id_usuario_cliente);
        if (!$Usuario->existe) {
            return;
        }
        $this->usuario = [
            'id'     => $Usuario->id,
            'nome'   => $Usuario->nome->nome(),
            'email'  => $Usuario->email->email(),
            'imagem' => $Usuario->imagem
        ];
    }
}
