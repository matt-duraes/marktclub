<?php

namespace App\Models\Api\ChatbotPerguntas;

use App\Classes\Geral\Status;
use App\Classes\ChatbotPerguntas\Ordem;
use App\Models\Site\ListarInterface;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class PerguntasModel extends ORM implements ListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_CHATBOT_PERGUNTAS;

    public function __construct(
        private readonly Pagina $pagina,
        private readonly Quantidade $quantidade = new Quantidade(null),
        private readonly Status $status = new Status(null),
        private readonly ?string $categoria = null,
        private readonly Ordem $ordem = new Ordem(null),
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'pergunta', 'resposta', 'status', 'data_criacao', 'data_atualizacao'])
            ->order($this->pegarOrdem())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_CHATBOT_CATEGORIA)
            ->join('id', 'categoria')
            ->campo(['categoria'], as: 'nome')
            ->where($this->pegarWhere(), false)
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista) ?? [];

        return $dado;
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        if($this->categoria) {
            $where[] = ['categoria', $this->categoria];
        }
        return $where;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $status = new Status();

        foreach ($lista as $r) {
            $retorno[] = [
                'id'                => $r->uuid,
                'categoria'         => $r->nome_categoria,
                'pergunta'          => $r->pergunta,
                'resposta'          => $r->resposta,
                'data_criacao'      => $r->data_criacao,
                'data_atualizacao'  => $r->data_atualizacao,
                'status'            => $status->indice($r->status),
            ];
        }
        return $retorno;
    }
}
