<?php

namespace App\Models\Api\ParceiroCashback;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroCashback\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class CashbackModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_CASHBACK;
    private int $idEmpresa;

    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade,
        private ?string $empresa,
        private Status $status,
        private Ordem $ordem
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function pegarRetorno(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'data_criacao', 'imagem', 'comissao_minima', 'url', 'status'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno($lista): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($lista as $r) {
            $retorno[] = [
                'id'              => $r->uuid,
                'titulo'          => $r->titulo,
                'imagem'          => arquivoPrivado($r->imagem),
                'url'             => $r->url,
                'data_criacao'    => $r->data_criacao,
                'comissao_minima' => $r->comissao_minima,
                'status'          => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [
            ['id_admin_empresa', 'json', $this->idEmpresa]
        ];
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }
}
