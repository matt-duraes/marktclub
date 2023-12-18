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
use Helpers\OrmHelper;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\Saude\Status;
use App\Classes\Saude\Operadora;
use App\Classes\Saude\Acomodacao;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\Saude\Operadoras\Amil\Regioes;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanoAmil;
use App\Classes\SaudeSimulacao\Status as SaudeSimulacaoStatus;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\Planos as PlanoCNU;

class ContratacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public array $usuario;
    public array $empresa;
    public array $simulacao;
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
    public Cpf $responsavel_cpf;
    public string $responsavel_rg;
    public Nome $responsavel_nome;
    public Email $email_pessoal;
    public Telefone $telefone_celular;
    public Telefone $telefone_residencial;
    public Telefone $telefone_comercial;
    public string $telefone_comercial_ramal;
    public string $endereco_logradouro;
    public EnderecoCep $endereco_cep;
    public EnderecoEstado $endereco_estado;
    public string $endereco_cidade;
    public string $endereco_bairro;
    public int $endereco_numero;
    public string $endereco_complemento;
    public Status $status;
    public string $responsavel_orgao_expedidor;

    protected int $id_saude_simulacao;
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected int $idSimulacao;
    protected int $id_usuario_cliente;
    protected int $id_admin_empresa;
    protected string $ormTabela = TABELA_SAUDE_CONTRATACAO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'status'             => 1
    ];
    protected array $ormBuscar = [
        'id_saude_simulacao', 'id_usuario_cliente', 'id_admin_empresa', 'documento_cpf', 'documento_rg',
        'orgao_expedidor', 'nome', 'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
        'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
        'email_pessoal', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
        'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
        'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'status',
    ];
    protected array $ormSalvar = [
        'id_saude_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
        'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
        'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
        'email_pessoal', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
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
        responsavel_orgao_expedidor|Orgão Expedidor Responsável|obrigatorio|vazio
        responsavel_nome|Nome Responsável|obrigatorio|vazio|valido
        email_pessoal|E-mail|obrigatorio|vazio|valido
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
     * @param SimulacaoEntity $Simulacao
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?SimulacaoEntity $Simulacao = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->id_saude_simulacao = $this->Simulacao->get('id');
    }

    /**
     * @throws Excecao
     */
    protected function regraPosInsert(): void
    {
        $this->Simulacao->status = new SaudeSimulacaoStatus(SaudeSimulacaoStatus::ENVIADO);
        $this->Simulacao->salvar();
    }

    protected function regraPosBuscar(): void
    {
        $this->buscarUsuario();
        $this->buscarEmpresa();
        $this->buscarSimulacao();
    }

    private function buscarUsuario(): void
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome', 'email_pessoal'],
            'object'
        );

        $this->usuario = [
            'id'    => $usuario->uuid,
            'nome'  => $usuario->nome,
            'email' => $usuario->email_pessoal
        ];
    }

    private function buscarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarUltimoRegistro(
            ['id', $this->id_admin_empresa],
            ['uuid', 'nome_fantasia'],
            'object'
        );

        $this->empresa = [
            'id'   => $empresa->uuid,
            'nome' => $empresa->nome_fantasia
        ];
    }

    private function buscarSimulacao(): void
    {
        $simulacao = (new OrmHelper(TABELA_SAUDE_SIMULACAO))->pegarUltimoRegistro(
            ['id', $this->id_saude_simulacao],
            ['uuid', 'titular', 'quantidade_dependente', 'operadora', 'acomodacao', 'plano',
                'regiao', 'valor_titular', 'lista_dependente', 'valor_total', 'data_criacao', 'status'],
            'object'
        );

        $this->simulacao = array_merge([
            'id'                    => $simulacao->uuid,
            'titular'               => $simulacao->titular,
            'quantidade_dependente' => $simulacao->quantidade_dependente,
            'valor_titular'         => $simulacao->valor_titular,
            'lista_dependente'      => $this->pegarListaDependente(jsonDecode($simulacao->lista_dependente)),
            'valor_total'           => $simulacao->valor_total,
            'data_criacao'          => $simulacao->data_criacao,
        ], $this->pegarIndicesPlano($simulacao));
    }

    private function pegarListaDependente($listaDependente)
    {
        $lista = [];

        if (!$listaDependente) {
            return $lista;
        }

        foreach ($listaDependente as $dependente) {
            $lista[] = [
                'data_nascimento'  => $dependente->data_nascimento,
                'valor'            => $dependente->valor
            ];
        }
        return $lista;
    }

    private function pegarIndicesPlano($simulacao)
    {
        $operadora = (new Operadora($simulacao->operadora))->indice();

        switch ($operadora) {
            case Operadora::AMIL:
                return [
                    'operadora'  => $operadora,
                    'plano'      => (new PlanoAmil($simulacao->plano))->indice(),
                    'regiao'     => (new Regioes($simulacao->regiao))->indice(),
                    'acomodacao' => (new Acomodacao($simulacao->acomodacao))->indice()
                ];
            case Operadora::CNU_FLORIANOPIS:
                return [
                    'operadora'  => $operadora,
                    'plano'      => (new PlanoCNU($simulacao->plano))->indice(),
                    'regiao'     => $simulacao->regiao,
                    'acomodacao' => (new Acomodacao($simulacao->acomodacao))->indice()
                ];
            case Operadora::UNIMED:
                return [
                    'operadora'  => $operadora,
                    'plano'      => $simulacao->plano,
                    'regiao'     => $simulacao->regiao,
                    'acomodacao' => (new Acomodacao($simulacao->acomodacao))->indice()
                ];
            case Operadora::UNIMED_SEGURO:
                return [
                    'operadora'  => $operadora,
                    'plano'      => $simulacao->plano,
                    'regiao'     => $simulacao->regiao,
                    'acomodacao' => (new Acomodacao($simulacao->acomodacao))->indice()
                ];
            default:
                return [
                    'operadora'  => '',
                    'plano'      => '',
                    'regiao'     => '',
                    'acomodacao' => ''
                ];
        }
    }
}
