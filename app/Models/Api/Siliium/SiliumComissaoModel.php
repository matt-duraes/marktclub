<?php

namespace App\Models\Api\Siliium;

use App\Helpers\Silium\Cashback;
use App\Models\Api\ParceiroCashback\CashbackEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\UsuarioCliente\ClienteModel;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use Status\StatusInterface;
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
     * @param Request|null $request
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @param array|int|string|null $id Lista de Id's
     *
     * @return int|float Saldo Total
     * @throws Excecao
     */
    public function pegarSaldo(array|int|string $id = null): int|float
    {
        $wherePadrao = $this->pegarWherePadrao();

        if (is_array($id)) {
            $wherePadrao[] = ['id', 'in', $id];
        } elseif (is_int($id) || is_string($id)) {
            $wherePadrao[] = ['id', $id];
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
     * @return array
     * @throws Excecao
     */
    public function buscarPorData(string $datas): array
    {
        $datas = explode(',', $datas);
        if (empty($datas)) {
            mensagemErro('Sem data', 'Envie pelo menos 1 data');
        }

        $retorno = [];
        foreach ($datas as $data) {
            $comissao = (new Cashback())->comissao($data);
            if (empty($comissao)) {
                continue;
            }

            $retorno[] = $this->montarRetornoComissao($comissao);
        }
        return $retorno;
    }

    /**
     * @param array $comissao
     *
     * @return array
     * @throws Excecao
     */
    private function montarRetornoComissao(array $comissao): array
    {
        $retorno = [];
        $ClienteEntity = new ClienteEntity();
        $CashbackEntity = new CashbackEntity();

        foreach ($comissao as $item) {
            $ClienteEntity->uuid($item->usuario);

            $empresa = (new ClienteModel())->buscarEmpresaPeloId($ClienteEntity->getId());
            $porcentagem = $CashbackEntity->uuid($item->programa);
            $valorComparacao = ($item->valor_compra * $porcentagem) / 100;
            $comissao = ($valorComparacao > $item->comissao_usuario)
                ? $item->comissao_usuario
                : $valorComparacao;

            $arr = [
                'uuid'             => uuid(),
                'id_venda'         => $item->id_venda,
                'usuario'          => $ClienteEntity->get('uuid'),
                'empresa'          => $empresa,
                'programa'         => $item->programa,
                'comissao_usuario' => number_format($comissao, 2, '.', ''),
                'comissao_total'   => number_format($item->comissao_usuario, 2, '.', ''),
                'valor_compra'     => number_format($item->valor_compra, 2, '.', ''),
                'moeda'            => $item->moeda,
                'data_compra'      => $item->data_compra,
                'status'           => 1
            ];

            $dados = $this
                ->dado($arr)
                ->insert();

            $retorno[] = [
                $arr, $dados
            ];
        }
        return $retorno;
    }

    /**
     * @param array|int|string           $id
     * @param StatusInterface|int|string $status
     *
     * @return array
     * @throws Excecao
     */
    public function atualizarStatus(array|int|string $id, StatusInterface|int|string $status): array
    {
        return $this
            ->dado([
                'status' => ($status instanceof StatusInterface) ? $status->numero() : $status
            ])
            ->where((is_array($id) && !empty($id)) ? ['id', 'in', $id] : ['id', $id])
            ->update();
    }
}
