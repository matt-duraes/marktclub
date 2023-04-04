<?php

namespace App\Models\Api\SolicitacaoSalavip;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\SolicitacaoVoucher\Ordem;
use System\Interface\ModelListarInterface;
use App\Classes\SolicitacaoSalavip\Empresa;
use App\Models\Api\SolicitacaoSalavip\Trait\WhereTrait;
use App\Models\Api\SolicitacaoSalavip\Trait\ValidarRequestTrait;

final class SalavipModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use WhereTrait;
    use ValidarRequestTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'empresa', 'codigo', 'data_validacao'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'empresa' => (new Empresa($r->empresa))->nome(),
                'codigo' => $r->codigo,
                'data' => $r->data_validacao
            ];
        }
        return $retorno;
    }
}
