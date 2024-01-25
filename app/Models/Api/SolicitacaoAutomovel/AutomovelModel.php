<?php

namespace App\Models\Api\SolicitacaoAutomovel;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoAutomovel\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class AutomovelModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_AUTOMOVEL;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->validarEmpresa();
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
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'endereco_estado', 'endereco_cidade', 'montadora',
                'modelo', 'versao', 'cor', 'mensagem', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'nome'
            ], 'usuario')
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

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

    private function montarDado(array $solicitacoes): array
    {
        if (empty($solicitacoes)) {
            return $solicitacoes;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $retorno[] = [
                'id'              => $solicitacao->uuid,
                'empresa'         => [
                    'nome' => $solicitacao->empresa_nome_fantasia
                ],
                'endereco_estado' => $solicitacao->endereco_estado,
                'endereco_cidade' => $solicitacao->endereco_cidade,
                'montadora'       => $solicitacao->montadora,
                'modelo'          => $solicitacao->modelo,
                'versao'          => $solicitacao->versao,
                'cor'             => $solicitacao->cor,
                'mensagem'        => $solicitacao->mensagem,
                'status'          => $Status->indice($solicitacao->status)
            ];
        }
        return $retorno;
    }
}
