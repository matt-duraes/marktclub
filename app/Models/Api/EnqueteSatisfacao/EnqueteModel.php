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
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class EnqueteModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ENQUETE_SATISFACAO;

    /**
     * @param Pagina          $pagina
     * @param Quantidade|null $quantidade
     * @param Ordem|null      $ordem
     * @param Status|null     $status
     */
    public function __construct(
        protected readonly Pagina $pagina,
        protected readonly ?Quantidade $quantidade = null,
        protected readonly ?Ordem $ordem = null,
        protected readonly ?Status $status = null
    ) {
        parent::__construct();
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
                'sistemas_clube', 'comentario', 'status', 'data_criacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
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

        $Suporte = new Suporte();
        $Navegar = new Navegar();
        $Procura = new Procura();
        $Atendimento = new Atendimento();
        $Status = new Status();

        $retorno = [];
        foreach ($respostas as $item) {
            $retorno[] = [
                'uuid'           => $item->uuid,
                'navegar'        => $Navegar->indice($item->navegar),
                'suporte'        => $Suporte->indice($item->suporte),
                'atendimento'    => $Atendimento->indice($item->atendimento),
                'procura'        => $Procura->indice($item->procura),
                'sistemas_clube' => $item->sistemas_clube,
                'comentario'     => $item->comentario,
                'status'         => $Status->indice($item->status),
                'data_criacao'   => $item->data_criacao
            ];
        }
        return $retorno;
    }
}
