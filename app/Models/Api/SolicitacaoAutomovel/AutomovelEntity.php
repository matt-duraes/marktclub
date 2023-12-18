<?php

namespace App\Models\Api\SolicitacaoAutomovel;

use ORM\Entity;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\EnderecoEstado;
use App\Classes\Solicitacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\DadoBaseModel;

final class AutomovelEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_AUTOMOVEL;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'endereco_estado', 'endereco_cidade',
        'montadora', 'modelo', 'versao', 'cor', 'mensagem', 'data_criacao',
        'data_atualizacao', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa', 'id_usuario_cliente', 'endereco_estado', 'endereco_cidade',
        'montadora', 'modelo', 'versao', 'cor', 'mensagem'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarInsert = '
        endereco_estado|Estado|obrigatorio|vazio|valido
        endereco_cidade|Cidade|obrigatorio|vazio
        montadora|Montadora|obrigatorio|vazio
        modelo|Modelo|obrigatorio|vazio
        mensagem|Mensagem|obrigatorio|vazio
    ';
    protected string $ormValidarUpdate = '
        status|Status|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    public string $endereco_cidade;
    public EnderecoEstado $endereco_estado;
    public string $montadora;
    public string $modelo;
    public string $versao;
    public string $cor;
    public string $mensagem;
    public Status $status;
    public array $empresa;
    public array $usuario;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraInsert(): void
    {
        $this->id_usuario_cliente = $this->idUsuario;
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::NOVO);
    }

    protected function regraPosBuscar(): void
    {
        $this->buscarEmpresa();
        $this->buscarUsuario();
    }

    private function buscarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarPrimeiroRegistro([
                ['id', $this->id_admin_empresa]
            ], ['cod', 'nome_fantasia'], 'object');

        if (empty($empresa->cod)) {
            return;
        }

        $this->empresa = [
            'id'   => $empresa->cod,
            'nome' => $empresa->nome_fantasia
        ];
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
