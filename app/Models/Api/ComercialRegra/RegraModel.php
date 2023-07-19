<?php

namespace App\Models\Api\ComercialRegra;

use ORM\ORM;
use stdClass;
use Http\Request;
use Helpers\OrmHelper;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class RegraModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_COMERCIAL_REGRA;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $lista = $this
            ->campo(['uuid', 'id_comercial_empresa', 'titulo', 'texto', 'data_criacao'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        if (vazio($lista)) {
            $this->paginacaoZero();
        }

        $lista->lista = $this->montarRetorno($lista->lista);
        return $lista;
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'titulo'       => $r->titulo,
                'texto'        => $r->texto,
                'data_criacao' => $r->data_criacao,
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [];
        $titulo = $this->request->titulo;
        if (!empty($titulo)) {
            $where[] = ['titulo', 'like', '%' . $titulo . '%'];
        }
        $empresa = $this->request->empresa;
        if (!empty($empresa)) {
            $where[] = ['id_comercial_empresa', 'json', (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($empresa)];
        }
        return $where;
    }
}
