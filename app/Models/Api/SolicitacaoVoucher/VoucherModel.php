<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Data;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\WhereTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ValidarRequestTrait;

final class VoucherModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarRequestTrait;
    use WhereTrait;


    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;
    private int $idEmpresa;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'tipo', 'data_criacao', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_PARCEIRO_LOJA)->join('cod', 'vinculo')->campo(['titulo'])
            ->tabela(TABELA_COMERCIAL_EMPRESA)->join('id', 'empresa')->campo(['nome_fantasia', 'cod'], 'empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $Status = new Status();
        $Tipo = new Tipo();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'empresa' => [
                    'id' => $r->empresa_cod,
                    'nome_fantasia' => $r->empresa_nome_fantasia
                ],
                'parceiro' => $r->titulo,
                'tipo' => $Tipo->indice($r->tipo),
                'data_criacao' => dataHoraBr($r->data_criacao),
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
