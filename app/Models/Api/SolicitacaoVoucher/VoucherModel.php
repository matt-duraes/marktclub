<?php

namespace App\Models\Api\SolicitacaoVoucher;

use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\TipoUsuario;
use App\Models\Api\SolicitacaoVoucher\Trait\ModelBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\ValidarRequestTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;

final class VoucherModel extends ORM
{
    use ValidarEmpresaTrait;
    use ValidarRequestTrait;
    use ModelBuscarTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;
    private int $idEmpresa;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        $this->validarEmpresa('empresa');
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @return stdClass
     */
    public function listarDados(): stdClass
    {
        $dado = $this->buscarVoucher();
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

        $Status = new Status();
        $Tipo = new Tipo();
        $TipoUsuario = new TipoUsuario();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'              => $r->cod,
                'parceiro'        => $r->titulo,
                'tipo'            => $Tipo->indice($r->tipo),
                'tipo_usuario'    => $TipoUsuario->indice($r->tipo_usuario),
                'data_vencimento' => $r->data_vencimento,
                'data_criacao'    => $r->data_criacao,
                'status'          => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
