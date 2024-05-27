<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\StatusComissao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

class SiliumComissaoEntity extends Entity
{
    protected string $ormTabela = TABELA_SILIUM_COMISSAO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'parceiro', 'valor_compra',
        'comissao_usuario', 'pontuacao', 'data_compra', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'id_usuario_cliente', 'parceiro', 'valor_compra',
        'comissao_usuario', 'pontuacao', 'data_compra', 'status'
    ];
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    public array $empresa;
    public string $parceiro;
    public string|array $usuario;
    public Dinheiro $valor_compra;
    public Dinheiro $comissao_usuario;
    public int $pontuacao;
    public Data $data_compra;
    public StatusComissao $status;

    public function __construct() {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarEmpresa();
        $this->pegarUsuario();
    }

    protected function regraSalvar(): void
    {
        $this->setarUsuario();
    }

    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['uuid', $this->usuario],
            ['id', 'id_admin_empresa'],
            'object'
        );

        if (empty($usuario->id)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário.');
        }

        $this->id_admin_empresa = $usuario->id_admin_empresa;
        $this->id_usuario_cliente = $usuario->id;
    }

    private function pegarEmpresa(): void
    {
        $OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $empresa = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_admin_empresa],
            ['uuid', 'titulo'],
            'object'
        );

        if (empty($empresa->uuid)) {
            $this->empresa = [
                'id'     => '',
                'titulo' => 'Não foi encontrado',
            ];
        }
        $this->empresa = [
            'id'     => $empresa->uuid,
            'titulo' => $empresa->titulo,
        ];
    }

    private function pegarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome'],
            'object'
        );

        if (empty($usuario->uuid)) {
            $this->usuario = [
                'id'   => '',
                'nome' => 'Não foi encontrado'
            ];
        }
        $this->usuario = [
            'id'   => $usuario->uuid,
            'nome' => $usuario->nome
        ];
    }
}
