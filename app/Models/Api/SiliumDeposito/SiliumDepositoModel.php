<?php

namespace App\Models\Api\SiliumDeposito;

use App\Classes\SiliumDeposito\Ordem;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoOperacao;
use App\Classes\SiliumDeposito\TipoResgate;
use Erro\Excecao;
use Modules\Data;
use Modules\Dinheiro;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumDepositoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;

    /**
     * @param Pagina       $pagina
     * @param Quantidade   $quantidade
     * @param Ordem        $ordem
     * @param string|null  $usuario
     * @param TipoConta    $tipoConta
     * @param TipoOperacao $tipoOperacao
     * @param TipoResgate  $tipoResgate
     * @param Data         $dataInicio
     * @param Data         $dataFinal
     * @param Status       $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $usuario = null,
        private readonly TipoConta $tipoConta = new TipoConta(),
        private readonly TipoOperacao $tipoOperacao = new TipoOperacao(),
        private readonly TipoResgate $tipoResgate = new TipoResgate(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @return void
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
        if (!$this->tipoConta->vazio() && !$this->tipoConta->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Conta informado não é válido.');
        }
        if (!$this->tipoOperacao->vazio() && !$this->tipoOperacao->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Operação informado não é válido.');
        }
        if (!$this->tipoResgate->vazio() && !$this->tipoResgate->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Resgate informado não é válido.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $depositos = $this
            ->campo([
                'uuid', 'nome_titular', 'documento_cpf', 'email', 'tipo_conta',
                'banco', 'agencia', 'conta', 'pontuacao', 'valor', 'data_deposito',
                'documento_anexo', 'status', 'tipo_operacao', 'tipo_resgate',
                'data_criacao', 'data_atualizacao'
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
        $depositos->lista = $this->montarRetorno($depositos->lista);
        return $depositos;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->tipoConta->valido()) {
            $where[] = ['tipo_conta', $this->tipoConta->numero()];
        }
        if ($this->tipoOperacao->valido()) {
            $where[] = ['tipo_operacao', $this->tipoOperacao->numero()];
        }
        if ($this->tipoResgate->valido()) {
            $where[] = ['tipo_resgate', $this->tipoResgate->numero()];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_deposito', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_deposito', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_deposito', '<=', $this->dataFinal->date()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->usuario)) {
            $where[] = ['nome', 'LIKE', "%$this->usuario%"];
        }
        return $where;
    }

    /**
     * @param array $depositos
     *
     * @return array
     */
    private function montarRetorno(array $depositos): array
    {
        if (empty($depositos)) {
            return $depositos;
        }

        $TipoConta = new TipoConta();
        $TipoOperacao = new TipoOperacao();
        $TipoResgate = new TipoResgate();
        $Status = new Status();
        $retorno = [];
        foreach ($depositos as $deposito) {
            $retorno[] = [
                'id'               => $deposito->uuid,
                'usuario'          => [
                    'id'   => $deposito->usuario_uuid,
                    'nome' => $deposito->usuario_nome
                ],
                'saque'            => [
                    'nome_titular'  => $deposito->nome_titular,
                    'documento_cpf' => $deposito->documento_cpf,
                    'tipo_conta'    => $TipoConta->indice($deposito->tipo_conta),
                    'banco'         => $deposito->banco,
                    'agencia'       => $deposito->agencia,
                    'conta'         => $deposito->conta,
                    'pontuacao'     => $deposito->pontuacao
                ],
                'valor'            => (new Dinheiro($deposito->valor))->decimal(),
                'data_deposito'    => $deposito->data_deposito,
                'tipo_operacao'    => $TipoOperacao->indice($deposito->tipo_operacao),
                'tipo_resgate'     => $TipoResgate->indice($deposito->tipo_resgate),
                'status'           => $Status->indice($deposito->status),
                'data_criacao'     => $deposito->data_criacao,
                'data_atualizacao' => $deposito->data_atualizacao
            ];
        }
        return $retorno;
    }
}
