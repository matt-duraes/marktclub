<?php

namespace App\Models\Api\View\Pagina;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ViewModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_VIEW_PAGINA;
    public Pagina $pagina;
    public Quantidade $quantidade;
    public stdClass $retorno;

    public function __construct(
        Request $request
    ) {
        parent::__construct();
        $this->set(lista: $request->dado());
        $this->retorno = $this->paginacaoZero();
        $this->buscarRegistro();
    }

    private function buscarRegistro()
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'url', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
        if (existeErro($dado, 'lista') || empty($dado->lista)) {
            return;
        }
        $dado->lista = $this->montarRetorno($dado->lista);
        $this->retorno = $dado;
    }

    private function montarRetorno(array $dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'url'    => $r->url,
                'status' => $r->status
            ];
        }
        return $retorno;
    }
}
