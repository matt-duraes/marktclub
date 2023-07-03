<?php

namespace App\Models\Api\Siliium;

use App\Helpers\Silium\Cashback;
use App\Models\Api\ParceiroCashback\CashbackEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumComissaoModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_COMISSAO;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    /**
     * @param Request $request
     */
    public function __construct(
        protected readonly Request $request
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @param string|int|null $id
     *
     * @return int|float Saldo Total
     * @throws Excecao
     */
    public function pegarSaldo(string|int $id = null): int|float
    {
        $wherePadrao = $this->pegarWherePadrao();

        if ($id !== null) {
            $wherePadrao[] = ['id', 'in', $id];
        }

        $comissoes = $this
            ->campo(['comissao_usuario'])
            ->where($wherePadrao)
            ->read();

        $saldo = 0;
        foreach ($comissoes as $comissao) {
            $saldo += $comissao->comissao_usuario;
        }

        $saldo *= 100;
        return $saldo;
    }

    /**
     * @return array[] [['empresa', $this->idEmpresa],['usuario', $this->idUsuario],['status', 1]]
     */
    private function pegarWherePadrao(): array
    {
        return [
            ['empresa', $this->idEmpresa],
            ['usuario', $this->idUsuario],
            ['status', 1]
        ];
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function comissaoDisponivel(): array
    {
        $wherePadrao = $this->pegarWherePadrao();

        $comissoesDisponiveis = $this
            ->campo(['id'])
            ->where($wherePadrao)
            ->read();

        $comissoes = [];
        foreach ($comissoesDisponiveis as $comissao) {
            $comissoes[] = $comissao->id;
        }

        return $comissoes;
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function retirarExtrato(): array
    {
        $extrato = $this
            ->campo([
                'uuid', 'comissao_usuario', 'data_compra', 'moeda', 'status'
            ])
            ->where([
                ['empresa', $this->idEmpresa],
                ['usuario', $this->idUsuario]
            ])
            ->order([
                ['status', 'ASC'],
                ['id', 'ASC']
            ])
            ->tabela(TABELA_CASHBACK_PROGRAMA)
            ->campo(['titulo'])
            ->join('programa', 'programa')
            ->read();

        return $this->montarRetornoExtrato($extrato);
    }

    /**
     * @param array $extrato
     *
     * @return array
     */
    private function montarRetornoExtrato(array $extrato): array
    {
        if (empty($extrato)) {
            return $extrato;
        }

        $retorno = [];
        foreach ($extrato as $item) {
            $retorno[] = [
                'uuid'     => $item->uuid,
                'programa' => $item->titulo,
                'data'     => [
                    'compra' => (new Data($item->data_compra))->data(),
                ],
                'ponto'    => round($item->comissao_usuario * 100),
                'comissao' => (object)[
                    'valor' => number_format($item->comissao_usuario, 2, ',', '.'),
                    'moeda' => $item->moeda,
                ],
                'status'   => $item->status
            ];
        }
        return $retorno;
    }

    /**
     * @param string $datas
     *
     * @return void|null
     * @throws Excecao
     */
    public function buscarPorData(string $datas)
    {
        $datas = explode(',', $datas);
        if (empty($datas)) {
            mensagemErro('Sem data', 'Envie pelo menos 1 data');
        }
        // TODO: Terminar Helper
        foreach ($datas as $data) {
            $comissao = (new Cashback())->comissao($data);
            if (!empty($comissao)) {
                $ClienteEntity = new ClienteEntity();
                $CashbackEntity = new CashbackEntity();

                foreach ($comissao as $item) {
                    $cliente = $ClienteEntity->uuid($item->usuario);
                }
            }
        }
    }
}
