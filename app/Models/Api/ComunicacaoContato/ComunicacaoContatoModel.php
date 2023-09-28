<?php

namespace App\Models\Api\ComunicacaoContato;

use App\Classes\ComunicacaoContato\Ordem;
use App\Classes\ComunicacaoContato\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
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

class ComunicacaoContatoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_CONTATO;

    /**
     * @param Pagina     $pagina
     * @param Quantidade $quantidade
     * @param Ordem      $ordem
     * @param Data       $dataCriacaoDe
     * @param Data       $dataCriacaoAte
     * @param Status     $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly Data $dataCriacaoDe = new Data(),
        private readonly Data $dataCriacaoAte = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->dataCriacaoDe->vazio() && !$this->dataCriacaoDe->valido()) {
            mensagemErro('Campo inválido!', 'A Data de Criação de início não está no formato válido.');
        }
        if (!$this->dataCriacaoAte->vazio() && !$this->dataCriacaoAte->valido()) {
            mensagemErro('Campo inválido!', 'A Data de Criação final não está no formato válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
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
        $dado = $this
            ->campo([
                'uuid', 'nome', 'telefone', 'email',
                'mensagem', 'status', 'data_criacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo(['nome_fantasia'], 'parceiro')
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

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        if ($this->dataCriacaoDe->valido() && $this->dataCriacaoAte->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataCriacaoDe->date(), $this->dataCriacaoAte->date() . ' 23:59:59']
            ];
        } elseif ($this->dataCriacaoDe->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataCriacaoDe->date()];
        } elseif ($this->dataCriacaoAte->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataCriacaoAte->date() . ' 23:59:59'];
        }

        return $where;
    }

    /**
     * @param array $contatos
     *
     * @return array
     */
    protected function montarRetorno(array $contatos): array
    {
        if (empty($contatos)) {
            return $contatos;
        }

        $retorno = [];
        foreach ($contatos as $contato) {
            $retorno[] = [
                'id'           => $contato->uuid,
                'nome'         => $contato->nome,
                'telefone'     => $contato->telefone,
                'email'        => $contato->email,
                'mensagem'     => $contato->mensagem,
                'parceiro'     => [
                    'nome' => $contato->parceiro_nome_fantasia
                ],
                'status'       => (new Status())->indice($contato->status),
                'data_criacao' => $contato->data_criacao
            ];
        }
        return $retorno;
    }
}
