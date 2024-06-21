<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\OrdemDeposito;
use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\TipoConta;
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

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly OrdemDeposito $ordem = new OrdemDeposito(),
        private readonly ?string $usuario = null,
        private readonly TipoConta $tipoConta = new TipoConta(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly StatusDeposito $status = new StatusDeposito()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

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

    public function listarDados(): stdClass
    {
        $depositos = $this
            ->campo([
                'uuid', 'valor', 'data_deposito', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new OrdemDeposito()))
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
            ->tabela(TABELA_SILIUM_SAQUE)
            ->join('id', 'id_silium_saque')
            ->campo([
                'uuid', 'nome_titular', 'documento_cpf', 'tipo_conta',
                'banco', 'agencia', 'conta', 'pontuacao'
            ], 'saque')
            ->read();

        $depositos->lista = $this->montarRetorno($depositos->lista);
        return $depositos;
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        /*if ($this->tipoConta->valido()) {
            $where[] = ['tipo_conta', $this->tipoConta->numero()];
        }*/
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

    private function montarRetorno(array $depositos): array
    {
        if (empty($depositos)) {
            return $depositos;
        }

        $TipoConta = new TipoConta();
        $Status = new StatusDeposito();
        $retorno = [];
        foreach ($depositos as $deposito) {
            $retorno[] = [
                'id'               => $deposito->uuid,
                'usuario'          => [
                    'id'    => $deposito->usuario_uuid,
                    'nome'  => $deposito->usuario_nome
                ],
                'saque'          => [
                    'id'            => $deposito->saque_uuid,
                    'nome_titular'  => $deposito->saque_nome_titular,
                    'documento_cpf' => $deposito->saque_documento_cpf,
                    'tipo_conta'    => $TipoConta->indice($deposito->saque_tipo_conta),
                    'banco'         => $deposito->saque_banco,
                    'agencia'       => $deposito->saque_agencia,
                    'conta'         => $deposito->saque_conta,
                    'pontuacao'     => $deposito->saque_pontuacao
                ],
                'valor'            => (new Dinheiro($deposito->valor))->banco(),
                'data_deposito'    => $deposito->data_deposito,
                'status'           => $Status->indice($deposito->status),
                'data_criacao'     => $deposito->data_criacao,
                'data_atualizacao' => $deposito->data_atualizacao
            ];
        }
        return $retorno;
    }
}
