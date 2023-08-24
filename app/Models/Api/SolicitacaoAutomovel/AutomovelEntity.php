<?php

namespace App\Models\Api\SolicitacaoAutomovel;

use ORM\Entity;
use Modules\EnderecoEstado;
use App\Classes\Solicitacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class AutomovelEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_AUTOMOVEL;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'id_admin_empresa', 'endereco_estado', 'endereco_cidade',
        'montadora', 'modelo', 'versao', 'cor', 'mensagem', 'data_criacao', 'data_atualizacao', 'status'
    ];
    protected array $ormInsert = [
        'id_usuario_cliente', 'id_admin_empresa', 'endereco_estado', 'endereco_cidade',
        'montadora', 'modelo', 'versao', 'cor', 'mensagem'
    ];
    protected array $ormSalvar = ['status'];
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
    protected int $id_usuario_cliente;
    protected int $id_admin_empresa;
    public EnderecoEstado $endereco_estado;
    public string $cidade;
    public string $montadora;
    public string $modelo;
    public string $versao;
    public string $cor;
    public string $mensagem;
    public Status $status;
    private int $idUsuario;
    private int $idEmpresa;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraInsert()
    {
        $this->id_usuario_cliente = $this->idUsuario;
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::NOVO);
    }
}
