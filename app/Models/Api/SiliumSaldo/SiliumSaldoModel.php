<?php

namespace App\Models\Api\SiliumSaldo;

use App\Classes\SiliumSaldo\Ordem;
use Erro\Excecao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumSaldoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_SALDO;

    /**
     *
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $usuario
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $usuario = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $saldos = $this
            ->campo([
                'uuid', 'saldo_silium', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
            ->read();
        $saldos->lista = $this->montarRetorno($saldos->lista);
        return $saldos;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date()];
        }
        return $where;
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->usuario) && !validarUuid($this->usuario)) {
            $where[] = ['nome', 'LIKE', "%$this->usuario%"];
        } elseif (!empty($this->usuario) && validarUuid($this->usuario)) {
            $where[] = ['uuid', $this->usuario];
        }
        return $where;
    }

    /**
     * @param array $saldos
     *
     * @return array
     */
    private function montarRetorno(array $saldos): array
    {
        if (empty($saldos)) {
            return $saldos;
        }

        $retorno = [];
        foreach ($saldos as $saldo) {
            $retorno[] = [
                'id'               => $saldo->uuid,
                'usuario'          => [
                    'id'   => $saldo->usuario_uuid,
                    'nome' => $saldo->usuario_nome
                ],
                'pontuacao'        => $saldo->saldo_silium,
                'data_criacao'     => $saldo->data_criacao,
                'data_atualizacao' => $saldo->data_atualizacao
            ];
        }
        return $retorno;
    }
}
