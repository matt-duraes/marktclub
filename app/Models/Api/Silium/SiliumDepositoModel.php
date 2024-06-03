<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\OrdemDeposito;
use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\Tipo;
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
        private readonly ?string $empresa = null,
        private readonly ?string $usuario = null,
        private readonly TipoConta $tipoConta = new TipoConta(),
        private readonly Tipo $tipo = new Tipo(),
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
        if (!$this->tipo->vazio() && !$this->tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
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
                'uuid', 'nome_titular', 'documento_cpf', 'banco', 'agencia',
                'conta', 'tipo_conta', 'valor', 'pontuacao', 'data_deposito',
                'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new OrdemDeposito()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->where($this->pegarWhereEmpresa(), false)
            ->campo([
                'uuid', 'titulo'
            ], 'empresa')
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

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->tipoConta->valido()) {
            $where[] = ['tipo_conta', $this->tipoConta->numero()];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
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

    private function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
        }
        return $where;
    }

    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->usuario) && !validarUuid($this->usuario)) {
            $where[] = ['nome', 'LIKE', "%$this->usuario%"];
        } else if (!empty($this->usuario) && validarUuid($this->usuario)) {
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
                'empresa'          => [
                    'id'     => $deposito->empresa_uuid,
                    'titulo' => $deposito->empresa_titulo
                ],
                'usuario'          => [
                    'id'    => $deposito->usuario_uuid,
                    'nome'  => $deposito->usuario_nome
                ],
                'dados_bancarios' => [
                    'nome_titular'     => $deposito->nome_titular,
                    'documento_cpf'    => $deposito->documento_cpf,
                    'tipo_conta'       => $TipoConta->indice($deposito->tipo_conta),
                    'banco'            => $deposito->banco,
                    'agencia'          => $deposito->agencia,
                    'conta'            => $deposito->conta,
                ],
                'pontuacao'        => $deposito->pontuacao,
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
