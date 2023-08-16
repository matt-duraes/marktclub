<?php

namespace App\Models\Api\Saude\Contratacao;

use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Modules\Genero;
use Modules\Telefone;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\Saude\Status;
use App\Classes\Saude\Operadora;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Saude\Documento\DocumentoEntity;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;

class ContratacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $id_simulacao;
    public Cpf $documento_cpf;
    public string $documento_rg;
    public string $orgao_expedidor;
    public Nome $nome;
    public Data $data_nascimento;
    public EstadoCivil $estado_civil;
    public string $naturalidade;
    public Genero $genero;
    public string $peso;
    public string $altura;
    public Nome $nome_mae;
    public ?Cpf $responsavel_cpf;
    public ?string $responsavel_rg;
    public ?Nome $responsavel_nome;
    public Email $email;
    public Telefone $telefone_celular;
    public ?Telefone $telefone_residencial;
    public Telefone $telefone_comercial;
    public ?string $telefone_comercial_ramal;
    public string $endereco_logradouro;
    public EnderecoCep $endereco_cep;
    public EnderecoEstado $endereco_estado;
    public string $endereco_cidade;
    public string $endereco_bairro;
    public int $endereco_numero;
    public ?string $endereco_complemento;
    public Status $status;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected string $ormTabela = TABELA_SAUDE_CONTRATACAO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'status'             => 1
    ];
    protected array $ormBuscar = [
        'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
        'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
        'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
        'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'status'
    ];
    protected array $ormSalvar = [
        'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
        'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
        'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
        'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'status'
    ];
    protected string $ormValidarInsert = '
        documento_cpf|CPF|obrigatorio|vazio|valido
        documento_rg|RG|obrigatorio|vazio
        orgao_expedidor|Orgão Expedidor|obrigatorio|vazio
        nome|Nome|obrigatorio|vazio|valido
        data_nascimento|Data de Nascimento|obrigatorio|vazio|valido
        estado_civil|Estado Civil|obrigatorio|vazio|valido
        naturalidade|Naturalidade|obrigatorio|vazio
        genero|Gênero|obrigatorio|vazio|valido
        peso|Peso|obrigatorio|vazio
        altura|Altura|obrigatorio|vazio
        nome_mae|Nome da mãe|obrigatorio|vazio|valido
        responsavel_cpf|CPF Responsável|obrigatorio|vazio|valido
        responsavel_rg|RG Responsável|obrigatorio|vazio
        responsavel_nome|Nome Responsável|obrigatorio|vazio|valido
        email|E-mail|obrigatorio|vazio|valido
        telefone_celular|Telefone Celular|obrigatorio|vazio|valido
        telefone_residencial|Telefone Residencial|valido
        telefone_comercial|Telefone Comercial|obrigatorio|vazio|valido
        endereco_logradouro|Endereço|obrigatorio|vazio
        endereco_cep|CEP|obrigatorio|vazio|valido
        endereco_estado|Estado|obrigatorio|valido
        endereco_cidade|Cidade|obrigatorio|vazio
        endereco_bairro|Bairro|obrigatorio|vazio
        endereco_numero|Número|obrigatorio|vazio
        endereco_complemento|Complemento
    ';

    /**
     * @param SimulacaoEntity $simulacaoEntity
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly SimulacaoEntity $simulacaoEntity
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function regraPosInsert(): void
    {
        if (
            !object_key_exists('operadora', $this->simulacaoEntity)
            || $this->simulacaoEntity->operadora !== (new Operadora(Operadora::CENTRAL_NACIONAL_UNIMED))->numero()
        ) {
            return;
        }

        (new DocumentoEntity($this->id_simulacao, new TipoUsuario(TipoUsuario::TITULAR)))->salvar();

        for ($i = 0; $i < $this->simulacaoEntity->quantidade_dependentes; $i++) {
            (new DocumentoEntity($this->id_simulacao, new TipoUsuario(TipoUsuario::DEPENDENTE)))->salvar();
        }
    }
}
