<?php

namespace App\Models\Api\SolicitacaoPremium;

use ORM\ORM;
use Http\Request;
use App\Classes\SolicitacaoPremium\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class PremiumModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    private int $idEmpresa;
    private string $de;
    private string $ate;
    public function __construct(
        private Request $request
    ) {

        parent::__construct();

        $this->validarEmpresa('empresa');
        $this->validarRequest();
        $this->setarPrimeiroUltimoDia();
    }

    public function listarDados(): array
    {
        $dado = $this
            ->campo(['status'])
            ->where([
                ['empresa', $this->idEmpresa],
                ['data_criacao', 'between', [$this->de, $this->ate]]
            ])
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'vinculo')
            ->campo(['id', 'titulo', 'limite_voucher'])
            ->where(['status', 5])
            ->read();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            if (!array_key_exists($r->id, $retorno)) {
                $retorno[$r->id] = [
                    'parceiro' => $r->titulo,
                    'total' => 0,
                    'ativo' => 0,
                    'validado' => 0,
                    'cancelado' => 0,
                    'limite' => empty($r->limite_voucher) ? 'Sem limite' : '',
                    'status' => ''
                ];
            }
            $retorno[$r->id]['total']++;
            if ($r->status == 1) {
                $retorno[$r->id]['ativo']++;
            } elseif ($r->status == 2) {
                $retorno[$r->id]['validado']++;
            } elseif ($r->status == 3) {
                $retorno[$r->id]['cancelado']++;
            }
        }
        return $this->colocarStatus($retorno);
    }
    private function colocarStatus($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $totalValido = $r['ativo'] + $r['validado'];
            if (!is_numeric($r['limite']) || $totalValido < $r['limite']) {
                $r['status'] = Status::LIVRE;
            } elseif ($r['ativo'] == $r['limite']) {
                $r['status'] = Status::ESGOTADO;
            } elseif ($r['limite'] == $totalValido) {
                $r['status'] = Status::GERADO;
            }
            $retorno[] = $r;
        }
        return $retorno;
    }
    private function validarRequest()
    {
    }
    private function setarPrimeiroUltimoDia()
    {
        $this->de = dataPrimeiroDiaMes(hoje());
        $this->ate = dataUltimoDiaMes(hoje(), formato: 'Y-m-d H:i:s');
    }
}
