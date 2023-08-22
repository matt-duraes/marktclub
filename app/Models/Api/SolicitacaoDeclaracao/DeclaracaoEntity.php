<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use ORM\Entity;
use Helpers\OrmHelper;
use App\Classes\Solicitacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class DeclaracaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja', 'data_criacao',
        'data_atualizacao', 'modelo', 'versao', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'status'             => 1,
        'id_parceiro_loja', 'modelo', 'versao'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarInsert = '
        id_parceiro_loja|Parceiro|obrigatorio|vazio
    ';
    protected string $ormValidarUpdate = '
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $idEmpresa;
    protected int $idUsuario;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected int $id_parceiro_loja;
    public array $usuario;
    public string|array $parceiro;
    public string $modelo;
    public string $versao;
    public Status $status;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        if (!$this->propriedadeExiste('idUsuario') || empty($this->idUsuario)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário para a declaração.');
        }
        $this->setarParceiro();
    }

    private function setarParceiro()
    {
        if (empty($this->parceiro)) {
            mensagemErro('Campo obrigatório!', 'O campo parceiro é obrigatório.');
        }
        $idParceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarIdPeloUuid($this->parceiro);
        if (empty($idParceiro)) {
            mensagemErro('Campo inválido!', 'Não foi encontrado um parceiro pelo dado enviado.');
        }
        $this->id_parceiro_loja = $idParceiro;
    }

    protected function regraPosBuscar()
    {
        $this->buscarParceiro();
        $this->buscarUsuario();
    }

    private function buscarParceiro()
    {
        $parceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarUltimoRegistro(
            where: ['id', $this->id_parceiro_loja],
            campo: ['uuid', 'titulo'],
            retorno: 'object'
        );
        if (!$parceiro) {
            $this->parceiro = [
                'id'     => '',
                'titulo' => 'Sem parceiro'
            ];
        }
        $this->parceiro = [
            'id'     => $parceiro->uuid,
            'titulo' => $parceiro->titulo
        ];
    }

    private function buscarUsuario()
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarUltimoRegistro(
            where: ['id', $this->id_usuario_cliente],
            campo: ['uuid', 'nome'],
            retorno: 'object'
        );
        if (!$usuario) {
            $this->usuario = [
                'id'   => '',
                'nome' => 'Sem usuário'
            ];
        }
        $this->usuario = [
            'id'   => $usuario->uuid,
            'nome' => $usuario->nome
        ];
    }
}
