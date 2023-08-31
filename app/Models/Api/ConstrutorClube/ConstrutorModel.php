<?php

namespace App\Models\Api\ConstrutorClube;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class ConstrutorModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_CONSTRUTOR_CLUBE;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $pesquisa = null,
        private Status $status = new Status(null)
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'data_criacao', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo(['uuid', 'titulo', 'nome_fantasia'], as: 'empresa')
            ->read();
        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'empresa'      => [
                    'id'     => $r->empresa_uuid,
                    'titulo' => !empty($r->empresa_titulo) ? $r->empresa_titulo : $r->empresa_nome_fantasia,
                ],
                'titulo'       => $r->titulo,
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'like', '%"' . $this->pesquisa . '"%'];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }
}
