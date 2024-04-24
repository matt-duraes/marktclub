<?php

namespace App\Models\Api\SolicitacaoPremium;

use App\Classes\ParceiroLoja\TipoLoja;
use ORM\ORM;
use Erro\Excecao;
use Http\Request;
use App\Classes\SolicitacaoPremium\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\SolicitacaoPremium\Trait\WhereTrait;
use App\Models\Api\SolicitacaoPremium\Trait\SetarDataTrait;
use App\Models\Api\SolicitacaoPremium\Trait\ValidarRequestTrait;

final class PremiumModel extends ORM
{
    use SetarDataTrait;
    use ValidarRequestTrait;
    use ValidarEmpresaTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;
    private string $de;
    private string $ate;
    private bool $mesAtualInteiro = false;
    private bool $mesAtual = false;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarRequest();
        $this->setarDadoDaData();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $dado = $this
            ->campo(['status'])
            ->where($this->pegarWhere(), false)
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('uuid', 'vinculo')
            ->campo([
                'id', 'titulo', 'limite_voucher'
            ])
            ->where(['tipo_loja', (new TipoLoja(TipoLoja::PREMIUM))->numero()])
            ->read();
        return $this->montarRetorno($dado);
    }

    /**
     * @param array $dado
     *
     * @return array
     */
    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            if (!array_key_exists($r->id, $retorno)) {
                $limite = empty($r->limite_voucher) ? 'Sem limite' : $r->limite_voucher;
                if (!$this->mesAtual) {
                    $limite = '-';
                }
                $retorno[$r->id] = [
                    'parceiro'   => $r->titulo,
                    'total'      => 0,
                    'ativo'      => 0,
                    'disponivel' => '-',
                    'validado'   => 0,
                    'cancelado'  => 0,
                    'limite'     => $limite,
                    'status'     => Status::SEM_STATUS
                ];
            }
            (int)$retorno[$r->id]['total']++;
            if ($r->status == 1) {
                $retorno[$r->id]['ativo']++;
            } elseif ($r->status == 2) {
                $retorno[$r->id]['validado']++;
            } elseif ($r->status == 3) {
                $retorno[$r->id]['cancelado']++;
            }
        }
        return $this->colocarDadosPosteriores($retorno);
    }

    /**
     * @param $dado
     *
     * @return array
     */
    private function colocarDadosPosteriores($dado): array
    {
        $livre = [];
        $esgotado = [];
        $gerado = [];
        $estourado = [];
        $semStatus = [];
        foreach ($dado as $r) {
            if (!$this->mesAtualInteiro) {
                $semStatus[] = $r;
                continue;
            }

            $totalValido = $r['ativo'] + $r['validado'];
            $r['disponivel'] = is_numeric($r['limite']) ? $r['limite'] - $r['validado'] - $r['ativo'] : '-';

            if (!is_numeric($r['limite']) || $totalValido < $r['limite']) {
                $r['status'] = Status::LIVRE;
                $livre[] = $r;
            } elseif ($totalValido > $r['limite']) {
                $r['status'] = Status::ESTOURADO;
                $estourado[] = $r;
            } elseif ($r['validado'] == $r['limite']) {
                $r['status'] = Status::ESGOTADO;
                $esgotado[] = $r;
            } elseif ($totalValido == $r['limite']) {
                $r['status'] = Status::GERADO;
                $gerado[] = $r;
            }
        }
        return array_merge($semStatus, $estourado, $livre, $gerado, $esgotado);
    }
}
