<?php

namespace App\Models\Api\ParceiroIndicacao;

use App\Classes\ParceiroIndicacao\Status;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class ParceiroIndicacaoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_PARCEIRO_INDICACAO;

    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade,
        private ?Status $status = null
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dados = $this
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
        $dados->lista = $this->montarRetorno($dados->lista);

        return $dados;
    }

    private function montarRetorno(array $dados): array
    {
        $retorno = [];
        foreach ($dados as $dado) {
            $retorno[$dado->id] = [
                'id'                => $dado->uuid,
                'nome'              => $dado->nome,
                'telefone'          => $dado->telefone,
                'email'             => $dado->email,
                'mensagem'          => $dado->mensagem,
                'status'            => (new Status())->indice($dado->status),
                'data_criacao'      => $dado->data_criacao,
                'data_atualizacao'  => $dado->data_atualizacao,
            ];
        }
        return $retorno;
    }
}
