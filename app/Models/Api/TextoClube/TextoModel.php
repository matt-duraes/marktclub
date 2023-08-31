<?php

namespace App\Models\Api\TextoClube;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class TextoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_TEXTO_CLUBE;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $empresa = null,
        private Tipo $tipo = new Tipo(null),
        private Status $status = new Status(null),
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'tipo', 'data_criacao', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
        $dado->lista = $this->montarDado($dado->lista);

        return $dado;
    }

    private function montarDado($dado)
    {
        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'titulo'       => $r->titulo,
                'tipo'         => $Tipo->indice($r->tipo),
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = [
                'id_admin_empresa',
                'json',
                (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa)
            ];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }
}
