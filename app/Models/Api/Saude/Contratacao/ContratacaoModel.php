<?php

namespace App\Models\Api\Saude\Contratacao;

use App\Classes\Saude\Ordem;
use App\Classes\Saude\Status;
use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ContratacaoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SAUDE_CONTRATACAO;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private Ordem $ordem = new Ordem(null),
        private ?string $pesquisa = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'documento_cpf', 'nome', 'data_criacao', 'data_atualizacao', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montardado($dado->lista);
        return $dado;
    }

    private function montardado(array $lista): array
    {
        if (empty($lista)) {
            return $lista;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($lista as $solicitacao) {
            $retorno[] = [
                'id'               => $solicitacao->uuid,
                'cpf'              => $solicitacao->documento_cpf,
                'nome'             => $solicitacao->nome,
                'data_criacao'     => $solicitacao->data_criacao,
                'data_atualizacao' => $solicitacao->data_atualizacao,
                'status'           => $Status->indice($solicitacao->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'like', '%' . $this->pesquisa . '%'];
        }
        return $where;
    }

    private function validarDado()
    {
        //
    }
}
