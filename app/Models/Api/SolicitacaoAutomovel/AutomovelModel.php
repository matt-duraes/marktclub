<?php

namespace App\Models\Api\SolicitacaoAutomovel;

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
use App\Classes\SolicitacaoAutomovel\Ordem;

final class AutomovelModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_AUTOMOVEL;

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
        $this->validarDado();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function montarDado($dado)
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
                'mensagem'        => $r->mensagem
            ];
        }
        return $retorno;
    }

    private function validarDado()
    {
    }
}
