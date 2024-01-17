<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\Solicitacao\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ChequeBonusModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $nome = null,
        private readonly ?string $empresa = null,
        private readonly TipoUsuario $tipoUsuario = new TipoUsuario(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
        $this->validarDados();
        parent::__construct();
    }

    private function validarDados(): void
    {
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data final não está no formato válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->tipoUsuario->vazio() && !$this->tipoUsuario->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de usuário informado não é válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'cod', 'tipo_usuario', 'nome',
                'dependente_nome', 'status', 'data_criacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->nome)) {
            $where[] = [
                'OR',
                ['nome', 'LIKE', '%' . $this->nome . '%'],
                ['dependente_nome', 'LIKE', '%' . $this->nome . '%']
            ];
        }

        if ($this->tipoUsuario->valido()) {
            $where[] = ['tipo_usuario', $this->tipoUsuario->numero()];
        }
        if ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date() . ' 23:59:59'];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function montarRetorno(array $solicitacoes): array
    {
        if (empty($solicitacoes)) {
            return $solicitacoes;
        }

        $TipoUsuario = new TipoUsuario();
        $Status = new Status();
        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $tipo = $TipoUsuario->indice($solicitacao->tipo_usuario);
            $nome = ($tipo === TipoUsuario::TITULAR) ? $solicitacao->nome : $solicitacao->dependente_nome;
            $retorno[] = [
                'id'           => $solicitacao->cod,
                'empresa'      => [
                    'nome' => $solicitacao->empresa_nome_fantasia
                ],
                'nome'         => $nome,
                'tipo_usuario' => $tipo,
                'status'       => $Status->indice($solicitacao->status),
                'data_criacao' => $solicitacao->data_criacao
            ];
        }
        return $retorno;
    }
}
