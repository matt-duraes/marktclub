<?php

namespace App\Models\Api\Saude;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\ValidarHelper;
use Http\Request;
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
use ReflectionClass;
use ReflectionProperty;

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
    protected string $ormTabela = TABELA_SAUDE_CONTRATACAO;
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'sexo', 'peso', 'altura',
        'filiacao', 'cpf_responsavel', 'rg_responsavel', 'nome_responsavel',
        'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'ramal', 'endereco', 'cep', 'estado', 'cidade', 'bairro', 'numero',
        'complemento', 'status'
    ];
    protected array $ormSalvar = [
        'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'sexo', 'peso', 'altura',
        'filiacao', 'cpf_responsavel', 'rg_responsavel', 'nome_responsavel',
        'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'ramal', 'endereco', 'cep', 'estado', 'cidade', 'bairro', 'numero',
        'complemento', 'status'
    ];

    /**
     * @param  ?Request  $request
     */
    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    /**
     * @return void
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $ValidarHelper = new ValidarHelper();
        $ReflectionClass = new ReflectionClass($this);
        $properties = $ReflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $nameProperty = $property->getName();
            $typeProperty = $property->getType()->getName();

            if (
                in_array(
                    $typeProperty,
                    [
                        'Cpf', 'Nome', 'Data', 'EstadoCivil', 'Genero', 'Email', 'Telefone', 'EnderecoCep',
                        'EnderecoEstado'
                    ],
                    true
                )
            ) {
                $this->$nameProperty = new $typeProperty($this->request->get($nameProperty));
            } else {
                $this->$nameProperty = $this->request->get($nameProperty, ($typeProperty === 'string') ? '' : 0);
            }
        }

        // phpcs:disable
        $ValidarHelper
            ->valor($this->documento_cpf, 'CPF', 'O CPF precisa ser válido')->obrigatorio()->valido()
            ->valor($this->documento_rg, 'RG', 'O RG precisa ser válido')->obrigatorio()->vazio()
            ->valor(
                $this->orgao_expedidor,
                'Orgão Expedidor',
                'O Orgão Expedidor precisa ser válido'
            )->obrigatorio()->vazio()
            ->valor($this->nome, 'Nome', 'O Nome precisa ser válido')->obrigatorio()->valido()
            ->valor(
                $this->data_nascimento,
                'Data de Nascimento',
                'A Data de Nascimento precisa ser válida'
            )->obrigatorio()->valido()
            ->valor(
                $this->estado_civil,
                'Estado Civil',
                'O Estado Civil precisa ser válido'
            )->obrigatorio()->valido()
            ->valor(
                $this->naturalidade,
                'Naturalidade',
                'A Naturalidade precisa ser válida'
            )->obrigatorio()->vazio()
            ->valor($this->sexo, 'Sexo', 'O Sexo precisa ser válido')->obrigatorio()->valido()
            ->valor(
                $this->peso,
                'Peso',
                'O Peso precisa ser válido'
            )->obrigatorio()->tamanho('>=', 1)
            ->valor(
                $this->altura,
                'Altura',
                'A Altura precisa ser válida'
            )->obrigatorio()->tamanho('>=', 1)
            ->valor(
                $this->filiacao,
                'Filiação',
                'A Filiação precisa ser válida'
            )->obrigatorio()->vazio()
            ->valor(
                $this->cpf_responsavel,
                'CPF do Responsável',
                'O CPF do Responsável precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor(
                $this->rg_responsavel,
                'RG do Responsável',
                'O RG do Responsável precisa ser válido'
            )->obrigatorio()->vazio()
            ->valor(
                $this->nome_responsavel,
                'Nome do Responsável',
                'O Nome do Responsável precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor(
                $this->email,
                'E-mail',
                'O E-mail precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor(
                $this->telefone_celular,
                'Telefone Celular',
                'O Telefone Celular precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor(
                $this->telefone_residencial,
                'Telefone Residencial',
                'O Telefone Residencial precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor(
                $this->telefone_comercial,
                'Telefone Comercial',
                'O Telefone Comercial precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor($this->ramal, 'Ramal', 'O Ramal precisa ser válido')->obrigatorio()->vazio()
            ->valor($this->cep, 'CEP', 'O CEP precisa ser válido')->obrigatorio()->vazio()->valido()
            ->valor(
                $this->estado,
                'Estado',
                'O Estado precisa ser válido'
            )->obrigatorio()->vazio()->valido()
            ->valor($this->cidade, 'Cidade', 'A Cidade precisa ser válida')->obrigatorio()->vazio()
            ->valor($this->bairro, 'Bairro', 'O Bairro precisa ser válido')->obrigatorio()->vazio()
            ->valor(
                $this->numero,
                'Número',
                'O Número precisa ser válido'
            )->obrigatorio()->vazio()->tamanho('>=', 1)
            ->valor($this->complemento, 'Complemento', 'O Complemento precisa ser válido')->obrigatorio()->vazio();
        // phpcs:enable

        try {
            if ((new SimulacaoModel())->validarSimulacao($this->id_simulacao) === null) {
                mensagemErro('Erro de validação', 'Não obtivemos sucesso ao validar sua contratação');
            }
        } catch (Excecao $excecao) {
            mensagemErro('Erro de validação', $excecao->getMessage());
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    public function regraPosInsert(): void
    {
        try {
            $SimulacaoModel = new SimulacaoModel();
            $SimulacaoModel->alterarStatusDaSimulacao($this->id_simulacao, new Status(Status::ENVIADO));
            $simulacao = $SimulacaoModel->pegarSimulacaoDocumento($this->id_simulacao);

            if (
                object_key_exists('operadora', $simulacao)
                && $simulacao->operadora === (new Operadora(Operadora::CENTRAL_NACIONAL_UNIMED))->numero()
            ) {
                (new DocumentoEntity(
                    $this->id_simulacao,
                    new TipoUsuario(TipoUsuario::TITULAR)
                ))->salvar();

                for ($i = 0; $i < $simulacao['quantidade_dependentes']; $i++) {
                    (new DocumentoEntity(
                        $this->id_simulacao,
                        new TipoUsuario(TipoUsuario::DEPENDENTE)
                    ))->salvar();
                }
            }
        } catch (Excecao $excecao) {
            mensagemErro('Erro de simulação', $excecao->getMessage());
        }
    }
}
