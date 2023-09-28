<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Ordem;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class EnqueteModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ENQUETE_SATISFACAO;

    /**
     * @param Pagina     $pagina
     * @param Quantidade $quantidade
     * @param Ordem      $ordem
     * @param Status     $status
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly Pagina $pagina = new Pagina(),
        protected readonly Quantidade $quantidade = new Quantidade(),
        protected readonly Ordem $ordem = new Ordem(),
        protected readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
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
                'uuid', 'navegar', 'procura', 'suporte', 'atendimento',
                'sistemas_clube', 'comentario', 'status', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->join('id', 'id_usuario_cliente')
            ->campo(['nome'], 'usuario')
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

        return $where;
    }

    /**
     * @param array $respostas
     *
     * @return array
     */
    protected function montarRetorno(array $respostas): array
    {
        if (empty($respostas)) {
            return $respostas;
        }

        $retorno = [];
        foreach ($respostas as $resposta) {
            $retorno[] = [
                'id'               => $resposta->uuid,
                'usuario'          => [
                    'nome' => $resposta->usuario_nome
                ],
                'navegar'          => (new Navegar())->indice($resposta->navegar),
                'procura'          => (new Procura())->indice($resposta->procura),
                'suporte'          => (new Suporte())->indice($resposta->suporte),
                'atendimento'      => (new Atendimento())->indice($resposta->atendimento),
                'sistemas_clube'   => $resposta->sistemas_clube,
                'comentario'       => $resposta->comentario,
                'status'           => (new Status())->indice($resposta->status),
                'data_criacao'     => $resposta->data_criacao,
                'data_atualizacao' => $resposta->data_atualizacao
            ];
        }
        return $retorno;
    }
}
