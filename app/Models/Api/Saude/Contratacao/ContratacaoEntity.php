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
use App\Models\Api\Saude\Simulacao\ContratarModel;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanoAmil;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\Planos as PlanoCNU;

class ContratacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public array          $usuario;
    public array          $empresa;
    public array          $simulacao;
    public Cpf            $documento_cpf;
    public string         $documento_rg;
    public string         $orgao_expedidor;
    public Nome           $nome;
    public Data           $data_nascimento;
    public EstadoCivil    $estado_civil;
    public string         $naturalidade;
    public Genero         $genero;
    public string         $peso;
    public string         $altura;
    public Nome           $nome_mae;
    public Cpf            $responsavel_cpf;
    public string         $responsavel_rg;
    public Nome           $responsavel_nome;
    public Email          $email_pessoal;
    public Telefone       $telefone_celular;
    public Telefone       $telefone_residencial;
    public Telefone       $telefone_comercial;
    public string         $telefone_comercial_ramal;
    public string         $endereco_logradouro;
    public EnderecoCep    $endereco_cep;
    public EnderecoEstado $endereco_estado;
    public string         $endereco_cidade;
    public string         $endereco_bairro;
    public int            $endereco_numero;
    public string         $endereco_complemento;
    public Status         $status;
    public string         $responsavel_orgao_expedidor;
    protected int         $id_saude_simulacao;
    protected int         $idSimulacao;
    protected int         $id_usuario_cliente;
    protected int         $id_admin_empresa;
    protected string      $ormTabela        = TABELA_SAUDE_CONTRATACAO;
    protected array       $ormInsert        = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'status'             => 1,
    ];
    protected array       $ormBuscar        = [
        'id_saude_simulacao',
        'id_usuario_cliente',
        'id_admin_empresa',
        'documento_cpf',
        'documento_rg',
        'orgao_expedidor',
        'nome',
        'data_nascimento',
        'estado_civil',
        'naturalidade',
        'genero',
        'peso',
        'altura',
        'nome_mae',
        'responsavel_cpf',
        'responsavel_rg',
        'responsavel_nome',
        'responsavel_orgao_expedidor',
        'email_pessoal',
        'telefone_celular',
        'telefone_residencial',
        'telefone_comercial',
        'telefone_comercial_ramal',
        'endereco_logradouro',
        'endereco_cep',
        'endereco_estado',
        'endereco_cidade',
        'endereco_bairro',
        'endereco_numero',
        'endereco_complemento',
        'status',
    ];
    protected array       $ormSalvar        = [
        'id_saude_simulacao',
        'documento_cpf',
        'documento_rg',
        'orgao_expedidor',
        'nome',
        'data_nascimento',
        'estado_civil',
        'naturalidade',
        'genero',
        'peso',
        'altura',
        'nome_mae',
        'responsavel_cpf',
        'responsavel_rg',
        'responsavel_nome',
        'responsavel_orgao_expedidor',
        'email_pessoal',
        'telefone_celular',
        'telefone_residencial',
        'telefone_comercial',
        'telefone_comercial_ramal',
        'endereco_logradouro',
        'endereco_cep',
        'endereco_estado',
        'endereco_cidade',
        'endereco_bairro',
        'endereco_numero',
        'endereco_complemento',
        'status',
    ];
    protected string      $ormValidarInsert = '
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
    private int           $idEmpresa;
    private ?int          $idUsuario        = null;

    /**
     * @param SimulacaoEntity $Simulacao
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?ContratarModel $Simulacao = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        if(is_null($this->Simulacao)) {
            mensagemErro(
                titulo: 'Erro!',
                mensagem: 'Não foi possível achar sua simulação, por favor, tente novamente.'
            );
        }
        $this->id_saude_simulacao = $this->Simulacao->id;
    }

    /**
     * @throws Excecao
     */
    protected function regraPosInsert(): void
    {
        $this->Simulacao->contratado();
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
            'email' => $usuario->email_pessoal,
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
            'nome' => $empresa->nome_fantasia,
        ];
    }

    private function buscarSimulacao(): void
    {
        $simulacao = (new OrmHelper(TABELA_SAUDE_SIMULACAO))->pegarUltimoRegistro(
            where: ['id', $this->id_saude_simulacao],
            campo: [
                'uuid',
                'id_saude_convenio',
                'simulacao'
            ],
            retorno: 'object'
        );

        $this->simulacao = $this->montarRetornoSimulacao($simulacao);
    }

    private function montarRetornoSimulacao($dado): array
    {
        $convenio = (new OrmHelper(TABELA_SAUDE_CONVENIO))->pegarUltimoRegistro(
            where: ['id', $dado->id_saude_convenio],
            campo: ['titulo']
        );
        $simulacao = jsonDecode($dado->simulacao, true, true);
        return [
            'convenio' => $convenio['titulo'],
            'plano' => $simulacao['plano'],
            'item' => $this->montarItemSimulacao($simulacao['item'] ?? []),
            'valor' => $this->montarValorSimulacao($simulacao['valor'] ?? []),
            'total' => $simulacao['total']
        ];
    }

    private function montarItemSimulacao($item): array
    {
        $retorno = [];
        foreach($item as $ind => $val) {
            $retorno[$ind] = $val;
        }
        return $retorno;
    }

    private function montarValorSimulacao($valor): array
    {
        $retorno = [];
        foreach($valor as $r) {
            $indice = $r['nome'] . ' ('. $r['data'] . ')';
            $retorno[$indice] = $r['valor'];
        }
        return $retorno;
    }
}
