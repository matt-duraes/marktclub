<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\OrdemSaque;
use App\Classes\Silium\StatusSaque;
use App\Classes\Silium\TipoConta;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumSaqueModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_SAQUE;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly OrdemSaque $ordem = new OrdemSaque(),
        private readonly ?string $usuario = null,
        private readonly TipoConta $tipoConta = new TipoConta(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly StatusSaque $status = new StatusSaque()
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
        $saques = $this->campo([
            'uuid', 'nome_titular', 'documento_cpf', 'banco', 'agencia',
            'conta', 'tipo_conta', 'pontuacao', 'status',
            'data_criacao', 'data_atualizacao'
        ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new OrdemSaque()))
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
            ->read();
        $saques->lista = $this->montarRetorno($saques->lista);
        return $saques;
    }

    public function listarSelect(): array
    {
        $saques = $this->campo([
            'uuid', 'pontuacao'
        ])
            ->where(['status', $this->status->numero(StatusSaque::AGUARDANDO)])
            ->order('data_criacao')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where([
                'OR',
                ['uuid', $this->usuario],
                ['nome', 'LIKE', '%' . $this->usuario . '%']
            ])
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
            ->read();
        return $this->montarSelect($saques);
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->tipoConta->valido()) {
            $where[] = ['tipo_conta', $this->tipoConta->numero()];
        }
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

    private function montarSelect(array $saques): array
    {
        if (empty($saques)) {
            return $saques;
        }

        $retorno = [];
        foreach ($saques as $saque) {
            $mensagem = $saque->usuario_nome . ' - ' . $saque->pontuacao . ' Pontos';
            $retorno[$saque->uuid] = $mensagem;
        }
        return $retorno;
    }

    private function montarRetorno(array $saques): array
    {
        if (empty($saques)) {
            return $saques;
        }

        $TipoConta = new TipoConta();
        $Status = new StatusSaque();
        $retorno = [];
        foreach ($saques as $saque) {
            $retorno[] = [
                'id'               => $saque->uuid,
                'usuario'          => [
                    'id'    => $saque->usuario_uuid,
                    'nome'  => $saque->usuario_nome
                ],
                'pagamento' => [
                    'nome_titular'     => $saque->nome_titular,
                    'documento_cpf'    => $saque->documento_cpf,
                    'tipo_conta'       => $TipoConta->indice($saque->tipo_conta),
                    'banco'            => $saque->banco,
                    'agencia'          => $saque->agencia,
                    'conta'            => $saque->conta,
                ],
                'pontuacao'        => $saque->pontuacao,
                'status'           => $Status->indice($saque->status),
                'data_criacao'     => $saque->data_criacao,
                'data_atualizacao' => $saque->data_atualizacao
            ];
        }
        return $retorno;
    }
}
