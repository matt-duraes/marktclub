<?php

namespace App\Models\Api\SolicitacaoAutomovel;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoAutomovel\Ordem;
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

final class AutomovelModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_AUTOMOVEL;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Data        $dataCriacaoDe
     * @param Data        $dataCriacaoAte
     * @param Status      $status
     * @param string|null $empresa
     * @param Ordem       $ordem
     */
    public function __construct(
        protected Pagina $pagina = new Pagina(null),
        protected Quantidade $quantidade = new Quantidade(null),
        protected Data $dataCriacaoDe = new Data(null),
        protected Data $dataCriacaoAte = new Data(null),
        protected Status $status = new Status(null),
        protected ?string $empresa = null,
        protected Ordem $ordem = new Ordem(null)
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
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    /**
     * @param $dado
     *
     * @return array
     */
    private function montarDado($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'              => $r->uuid,
                'endereco_estado' => $r->endereco_estado,
                'endereco_cidade' => $r->endereco_cidade,
                'montadora'       => $r->montadora,
                'modelo'          => $r->modelo,
                'versao'          => $r->versao,
                'cor'             => $r->cor,
                'mensagem'        => $r->mensagem,
                'status'          => (new Status($r->status))->indice(),
            ];
        }
        return $retorno;
    }
}
