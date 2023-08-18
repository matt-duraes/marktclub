<?php

namespace App\Models\Api\ComunicacaoHistorico;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Models\Site\ListarInterface;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ComunicacaoHistorico\Ordem;

final class HistoricoModel extends ORM implements ListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_HISTORICO;

    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade,
        private ?string $titulo = null,
        private Data $dataInicio = new Data(null),
        private Data $dataFinal = new Data(null),
        private Status $status = new Status(null),
        private Botao $publicado = new Botao(null),
        private Ordem $ordem = new Ordem(null),
    ) {
        parent::__construct();
        $this->validarDado();
    }

    private function validarDado()
    {
        if (!$this->dataInicio->vazio() && !$this->dataInicio->valido()) {
            mensagemErro('Dado inválido', 'A data de inicio não é válida.');
        } elseif (!$this->dataFinal->vazio() && !$this->dataFinal->valido()) {
            mensagemErro('Dado inválido', 'A data final não é válida.');
        } elseif (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Dado inválido', 'O status não é válido.');
        } elseif (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Dado inválido', 'A ordem não é válida.');
        }
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'data_inicio', 'data_final', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->campo(['uuid', 'titulo'], as: 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function montarDado(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $statusAtual = $Status->indice($r->status);
            $publicado = (new Publicado(
                new Data($r->data_inicio),
                new Data($r->data_final),
                $statusAtual == Status::ATIVO
            ))->indice();

            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'parceiro'    => [
                    'id'     => $r->parceiro_cod,
                    'titulo' => $r->parceiro_titulo,
                ],
                'data_inicio' => $r->data_inicio,
                'data_final'  => $r->data_final,
                'publicado'   => $publicado,
                'status'      => $statusAtual
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [];
        $publicadoVazio = $this->publicado->vazio();
        if (!$publicadoVazio && $this->publicado->valor() == 'sim') {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_final', '>=', hoje()],
                ['status', 1]
            ];
        } elseif (!$publicadoVazio && $this->publicado->valor() == 'nao') {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', 1]
            ];
        }
        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'like', $this->titulo . '%'];
        }
        if ($this->dataInicio->valido() && $publicadoVazio) {
            $where[] = ['data_inicio', '<=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido() && $publicadoVazio) {
            $where[] = ['data_final', '>=', $this->dataFinal->date()];
        }
        if ($this->status->valido() && $publicadoVazio) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }
}
