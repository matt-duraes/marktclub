<?php

namespace App\Models\Api\IndicacaoNovoParceiro;

use App\Classes\IndicacaoNovoParceiro\Status;
use Http\Request;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class IndicacaoNovoParceiroModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_INDICACAO_NOVO_PARCEIRO;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dados = $this
            ->select()
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
                'nome_indicado'     => $dado->nome_indicado,
                'telefone_indicado' => $dado->telefone_indicado,
                'email_indicado'    => $dado->email_indicado,
                'mensagem'          => $dado->mensagem,
                'status'            => (new Status())->nome($dado->status),
                'data_criacao'      => $dado->data_criacao,
            ];
        }
        return $retorno;
    }
}
