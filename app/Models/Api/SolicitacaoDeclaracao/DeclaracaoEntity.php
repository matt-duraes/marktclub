<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use ORM\Entity;
use Erro\Excecao;
use Helpers\OrmHelper;
use App\Classes\Solicitacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class DeclaracaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public array $empresa;
    public array $usuario;
    public array|string $parceiro;
    public ?string $modelo;
    public ?string $versao;
    public Status $status;
    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja',
        'modelo', 'versao', 'data_criacao', 'data_atualizacao', 'status'
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
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected int $id_parceiro_loja;

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
    public function regraInsert(): void
    {
        if (!$this->propriedadeExiste('idUsuario') || empty($this->idUsuario)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário para a declaração.');
        }
        $this->setarParceiro();
    }

    /**
     * @throws Excecao
     */
    private function setarParceiro(): void
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

    protected function regraPosBuscar(): void
    {
        $this->buscarEmpresa();
        $this->buscarUsuario();
        $this->buscarParceiro();
    }

    private function buscarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarUltimoRegistro(
                ['id', $this->id_admin_empresa],
                ['uuid', 'nome_fantasia'],
                'object'
            );
        if (empty($empresa->uuid)) {
            $this->empresa = [
                'id'   => '',
                'nome' => 'Sem empresa'
            ];
            return;
        }
        $this->empresa = [
            'id'   => $empresa->uuid,
            'nome' => $empresa->nome_fantasia
        ];
    }

    private function buscarUsuario(): void
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))
            ->pegarUltimoRegistro(
                ['id', $this->id_usuario_cliente],
                ['uuid', 'nome', 'email_pessoal'],
                'object'
            );
        if (empty($usuario->uuid)) {
            $this->usuario = [
                'id'    => '',
                'nome'  => 'Sem usuário',
                'email' => ''
            ];
            return;
        }
        $this->usuario = [
            'id'    => $usuario->uuid,
            'nome'  => $usuario->nome,
            'email' => $usuario->email_pessoal
        ];
    }

    private function buscarParceiro(): void
    {
        $parceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))
            ->pegarUltimoRegistro(
                ['id', $this->id_parceiro_loja],
                ['uuid', 'titulo'],
                'object'
            );
        if (empty($parceiro->uuid)) {
            $this->parceiro = [
                'id'   => '',
                'nome' => 'Sem parceiro'
            ];
            return;
        }
        $this->parceiro = [
            'id'   => $parceiro->uuid,
            'nome' => $parceiro->titulo
        ];
    }
}
