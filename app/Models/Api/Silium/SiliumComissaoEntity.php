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
        'id_usuario_cliente', 'parceiro', 'valor_compra',
        'comissao_usuario', 'pontuacao', 'data_compra', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_usuario_cliente', 'parceiro', 'valor_compra',
        'comissao_usuario', 'pontuacao', 'data_compra', 'status'
    ];
    protected int $id_usuario_cliente;
    public string $parceiro;
    public string|array $usuario;
    public Dinheiro $valor_compra;
    public Dinheiro $comissao_usuario;
    public int $pontuacao;
    public Data $data_compra;
    public StatusComissao $status;

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
        $this->setarUsuario();
        $this->pontuacao = $this->comissao_usuario->decimal() * 100;
    }

    protected function regraPosSalvar(): void
    {
        if ($this->status->indice() === StatusComissao::LIBERADO) {
            $this->setarPontuacao();
        }
    }

    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $id = $OrmHelper->pegarIdPeloUuid($this->usuario);

        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Não foi possível achar um usuário.');
        }
        $this->id_usuario_cliente = $id;
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

    private function setarPontuacao(): void
    {
        $SiliumSaldoEntity = new SiliumSaldoEntity();
        $SiliumSaldoEntity->buscar(['id_usuario_cliente' => $this->id_usuario_cliente], false);

        $dados = ['saldo_silium' => 0];
        if (!empty($SiliumSaldoEntity->id)) {
            $dados = [
                'saldo_silium'  => $SiliumSaldoEntity->saldo_silium + $this->pontuacao,
                'data_validade' => dataAdicionar(hoje(), 1, 'ano')
            ];
        }
        $SiliumSaldoEntity->set(lista: $dados);
        $SiliumSaldoEntity->salvar();
    }
}
