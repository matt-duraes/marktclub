<?php

namespace App\Models\Api\SiliumComissao;

use App\Classes\SiliumComissao\Status;
use App\Models\Api\SiliumSaldo\SiliumSaldoEntity;
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
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'parceiro', 'valor_compra', 'comissao_usuario', 'pontuacao',
        'data_compra', 'status'
    ];
    protected string $ormValidarSalvar = '
        parceiro|Nome do Parceiro/Loja|obrigatorio|vazio
        valor_compra|Valor da Compra|obrigatorio|vazio|valido
        comissao_usuario|Comissão do Usuário|obrigatorio|vazio|valido
        data_compra|Data da Compra|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected int $idEmpresa;
    protected int $idUsuario;

    public string $parceiro;
    public string|array $empresa;
    public string|array $usuario;
    public Dinheiro $valor_compra;
    public Dinheiro $comissao_usuario;
    public int $pontuacao;
    public Data $data_compra;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarUsuario();
    }

    protected function regraSalvar(): void
    {
        $this->validarDataFutura();
        $this->setarUsuarioEmpresa();
        $this->calcularPontuacao();
    }

    protected function regraPosSalvar(): void
    {
        if ($this->status->indice() === Status::LIBERADO) {
            $this->setarPontuacao();
        }
    }

    private function setarUsuarioEmpresa(): void
    {
        $usuario = is_string($this->usuario) ? $this->usuario : $this->usuario['id'];
        if (!validarUuid($usuario)){
            mensagemErro(
                'Usuário inválido!',
                'A identificação de usuário informada não é válida.',
                localhost: 'O usuário informado não é um UUID'
            );
        }

        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['uuid', $usuario],
            ['id', 'id_admin_empresa'],
            'object'
        );

        if (empty($usuario->id)) {
            mensagemErro(
                'Usuário inválido!',
                'Não foi possível achar um usuário.',
                localhost: 'Não existe um usuário na base com esse UUID'
            );
        }
        $this->idEmpresa = $usuario->id_admin_empresa;
        $this->idUsuario = $usuario->id;
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

    private function validarDataFutura(): void
    {
        if ($this->data_compra->date() > hoje()) {
            mensagemErro(
                'Data da compra inválida!',
                'Sua data de compra não pode ser maior que ' . hoje(true),
                localhost: 'Sua data está no futuro.'
            );
        }
    }

    private function calcularPontuacao(): void
    {
        $this->pontuacao = $this->comissao_usuario->decimal() * 100;
    }

    private function setarPontuacao(): void
    {
        $SiliumSaldoEntity = new SiliumSaldoEntity();
        $SiliumSaldoEntity->buscar([
            'id_usuario_cliente', $this->id_usuario_cliente
        ], false);

        $saldo = [];
        if (empty($SiliumSaldoEntity->id)) {
            $saldo = [
                'id_usuario_cliente' => $this->id_usuario_cliente,
                'saldo_silium'       => $this->pontuacao
            ];
        } else {
            $saldo = [
                'saldo_silium' => $SiliumSaldoEntity->saldo_silium + $this->pontuacao,
            ];
        }
        $SiliumSaldoEntity->set(lista: $saldo);
        $SiliumSaldoEntity->salvar();
    }
}
