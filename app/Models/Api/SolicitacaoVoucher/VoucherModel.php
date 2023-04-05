<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ModelBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ValidarRequestTrait;

final class VoucherModel extends ORM
{
    use ValidarEmpresaTrait;
    use ValidarRequestTrait;
    use ModelBuscarTrait;


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
        $dado = $this->buscarVoucher();

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
                'data_criacao' => $r->data_criacao,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
