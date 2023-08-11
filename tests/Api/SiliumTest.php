<?php

namespace Tests\Api;

use App\Classes\Silium\TipoConta;
use Erro\Excecao;
use Tests\Api\Token\Clube;

class SiliumTest extends Clube
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return SiliumTest
     * @throws Excecao
     */
    public function pegarSaldoTest(): SiliumTest
    {
        $this->api('silium:saldo');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/silium/saldo');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    /**
     * @return SiliumTest
     * @throws Excecao
     */
    public function retirarExtratoTest(): SiliumTest
    {
        $this->api('silium:extrato');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/silium/extrato');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    /**
     * @return SiliumTest
     * @throws Excecao
     */
    public function realizarSaqueTest(): SiliumTest
    {
        $this->api('silium:saque');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'       => $this->nomeCompleto(),
                'documento_cpf' => $this->cpf(),
                'banco'         => 'Caixa',
                'agencia'       => '6153',
                'conta'         => '56184-5',
                'tipo_conta'    => TipoConta::CONTA_CORRENTE
            ])
            ->post('/silium/saque');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }
}
