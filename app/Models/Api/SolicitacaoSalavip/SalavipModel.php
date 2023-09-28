<?php

namespace App\Models\Api\SolicitacaoSalavip;

use App\Classes\SolicitacaoSalavip\Empresa;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Models\Api\SolicitacaoSalavip\Trait\ValidarRequestTrait;
use App\Models\Api\SolicitacaoSalavip\Trait\WhereTrait;
use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class SalavipModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use WhereTrait;
    use ValidarRequestTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    /**
     * @param Request $request
     */
    public function __construct(
        protected Request $request
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'cod', 'empresa', 'codigo', 'data_validacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @param array $dado
     *
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'      => $r->cod,
                'empresa' => (new Empresa($r->empresa))->nome(),
                'codigo'  => $r->codigo,
                'data'    => $r->data_validacao
            ];
        }
        return $retorno;
    }
}
