<?php

namespace App\Models\Api\Saude\Contratacao;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Saude\Documento\DocumentoEntity;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use Modules\EstadoCivil;
use Modules\Genero;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class ContratacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public int $id_simulacao;
    public Cpf $documento_cpf;
    public string $documento_rg;
    public string $orgao_expedidor;
    public Nome $nome;
    public Data $data_nascimento;
    public EstadoCivil $estado_civil;
    public string $naturalidade;
    public Genero $sexo;
    public float $peso;
    public float $altura;
    public string $filiacao;
    public Cpf $cpf_responsavel;
    public string $rg_responsavel;
    public Nome $nome_responsavel;
    public Email $email;
    public Telefone $telefone_celular;
    public Telefone $telefone_residencial;
    public Telefone $telefone_comercial;
    public string $ramal;
    public string $endereco;
    public EnderecoCep $cep;
    public EnderecoEstado $estado;
    public string $cidade;
    public string $bairro;
    public int $numero;
    public string $complemento;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected string $ormTabela = TABELA_SAUDE_CONTRATACAO;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario'       => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'sexo', 'peso', 'altura',
        'filiacao', 'cpf_responsavel', 'rg_responsavel', 'nome_responsavel',
        'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'ramal', 'endereco', 'cep', 'estado', 'cidade', 'bairro', 'numero',
        'complemento', 'status'
    ];
    protected string $ormValidarInsert = '
        documento_cpf|CPF|obrigatorio|vazio|valido
        documento_rg|RG|obrigatorio|vazio
        orgao_expedidor|Orgão Expedidor|obrigatorio|vazio
        nome|Nome|obrigatorio|vazio|valido
        data_nascimento|Data de Nascimento|obrigatorio|vazio|valido
        estado_civil|Estado Civil|obrigatorio|vazio|valido
        naturalidade|Naturalidade|obrigatorio|vazio
        sexo|Gênero|obrigatorio|vazio|valido
        peso|Peso|vazio
        altura|Altura|vazio
        filiacao|Filiação|vazio
        cpf_responsavel|CPF Responsável|vazio|valido
        rg_responsavel|RG Responsável|vazio
        nome_responsavel|Nome Responsável|vazio|valido
        email|E-mail|vazio|valido
        telefone_celular|Telefone Celular|obrigatorio|vazio|valido
        telefone_residencial|Telefone Residencial|vazio|valido
        telefone_comercial|Telefone Comercial|vazio|valido
        ramal|Ramal|vazio
        endereco|Endereço|obrigatorio|vazio
        cep|CEP|obrigatorio|vazio|valido
        estado|Estado|obrigatorio|valido
        cidade|Cidade|obrigatorio|vazio
        bairro|Bairro|obrigatorio|vazio
        numero|Númerp|obrigatorio|vazio
        complemento|Complemento|vazio
    ';

    /**
     * @param  SimulacaoEntity  $simulacaoEntity
     */
    public function __construct(
        protected readonly SimulacaoEntity $simulacaoEntity
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return void
     */
    public function regraInsert(): void
    {
        $this->simulacaoEntity->uuid($this->id_simulacao);
    }

    /**
     * @return void
     * @throws Excecao
     */
    public function regraPosInsert(): void
    {
        $this->simulacaoEntity->status = new Status(Status::ENVIADO);
        $this->simulacaoEntity->salvar();

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
