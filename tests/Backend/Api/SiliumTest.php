<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Token\Clube;
use App\Classes\Silium\TipoConta;

class SiliumTest extends Clube
{
    public function __construct()
    {
        parent::__construct();
        $this
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->tabela(TABELA_SILIUM_COMISSAO)
            ->resetar();

        $this->pegarToken('91122519095', 'Teste@1324');
    }

    /**
     * @return SiliumTest
     * @throws Excecao
     */
    public function pegarSaldoTest(): SiliumTest
    {
        $this
            ->Curl
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
        $this
            ->Curl
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
        $this
            ->Curl
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
