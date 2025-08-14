<?php

namespace App\Models\Api\CampanhaVoucher;

use App\Classes\CampanhaVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\DataHora;
use ORM\Entity;

class CampanhaVoucherEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Cpf $documento_cpf;
    public string $voucher;
    public DataHora $data_vencimento;
    public Status $status;
    protected string $ormTabela = TABELA_CAMPANHA_VOUCHER;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'documento_cpf', 'voucher',
        'data_vencimento', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormUpdate = [
        'data_vencimento', 'status'
    ];
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        $this->validarVencimento();
        parent::__construct();
    }

    private function validarVencimento(): void
    {
        $ormHelper = new OrmHelper($this->ormTabela);
        $Status = new Status();
        $vouchers = $ormHelper->listar(
            ['id', 'data_vencimento'],
            ['status', $Status->numero(Status::NAO_RESGATADO)]
        );

        foreach ($vouchers as $voucher) {
            if ($voucher->data_vencimento < date('Y-m-d H:i:s')) {
                $ormHelper->atualizar(
                    ['status' => $Status->numero(Status::VENCIDO)],
                    $voucher->id
                );
            }
        }
    }

    /**
     * @param bool $cpf
     *
     * @return string
     * @throws Excecao
     */
    public function resgatarVoucher(bool $cpf = false): string
    {
        if ($cpf) {
            $where = [
                ['documento_cpf', $this->pegarCpfUsuario()]
            ];
        } else {
            $where = [
                ['id_admin_empresa', $this->idEmpresa],
                ['id_usuario_cliente', $this->idUsuario]
            ];
        }

        $ormHelper = new OrmHelper($this->ormTabela);
        $Status = new Status();
        $voucher = $ormHelper
            ->campo(['id', 'voucher', 'data_vencimento', 'status'])
            ->where($where)
            ->primeiro();

        if (empty($voucher)) {
            mensagemErro(
                'Voucher não encontrado!',
                'Não há voucher para resgatar!'
            );
        }

        if ($Status->indice($voucher->status) === Status::VENCIDO) {
            mensagemErro(
                'Voucher vencido!',
                'Não há voucher disponivel para resgatar!'
            );
        } elseif ($Status->indice($voucher->status) === Status::NAO_RESGATADO) {
            $ormHelper->atualizar([
                'data_atualizacao' => date('Y-m-d H:i:s'),
                'status'           => $Status->numero(Status::RESGATADO)
            ], $voucher->id);
        }
        return $voucher->voucher;
    }

    /**
     * @return string
     * @throws Excecao
     */
    private function pegarCpfUsuario(): string
    {
        $ormHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $cpfUsuario = $ormHelper->campo(['documento'])->where(['id', $this->idUsuario])->primeiro();

        if (empty($cpfUsuario)) {
            mensagemErro(
                'Usuário sem CPF!',
                'Contate o suporte para que possa resgatar o voucher!'
            );
        }
        return $cpfUsuario->documento;
    }

    /**
     * @return bool
     * @throws Excecao
     */
    public function verificaVoucher(): bool
    {
        $ormHelper = new OrmHelper($this->ormTabela);
        $voucher = $ormHelper
            ->campo(['id'])
            ->where([
                ['id_admin_empresa', $this->idEmpresa],
                ['id_usuario_cliente', $this->idUsuario]
            ])->primeiro();

        return !empty($voucher);
    }
}
