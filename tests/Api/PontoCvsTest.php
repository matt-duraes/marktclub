<?php


namespace Tests\Api;

use Tests\Tests;
use App\Classes\PontoCvs\Helper;

final class PontoCvsTest extends Tests
{
    private array $dadoSalvo;

    public function __construct()
    {
        $this->api('login:painel');
        $this->Curl->loginPainel('01234567890', 'Teste@1324');

        parent::__construct();
    }

    public function naoPodeResgatarValorMenorQuePontoMinimoTest()
    {
        $pontoMinimo = Helper::PONTO_MINIMO;
        $ponto = $pontoMinimo - 1;

        $this->salvarResgate($ponto);
        return $this->erroPadrao('Você deve enviar pelo menos ' . $pontoMinimo . ' para solicitar resgate.');
    }

    public function naoPodeResgatarValorMaiorQuePontoExistenteTest()
    {
        $this->salvarResgate(999999999);
        return $this->erroPadrao('Quantidade de pontos maior informado é maior que seu saldo atual.');
    }

    public function salvandoSolicitacaoSemErroTest()
    {
        $this->api('ponto_cvs:salvar');
        $Curl = $this
            ->Curl
            ->loginPainel()
            ->body([
                'ponto_solicitado' => 100
            ])
            ->post('/ponto-cvs');

        $dado = $Curl->array();
        $this->dadoSalvo = $dado['dado'] ?? [];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.ponto_solicitado', 100)
            ->checkIndiceIgual('dado.status', 'solicitado')
            ->checkIndiceIgual('dado.voucher', '')
            ->checkIndiceIgual('dado.data_voucher', '');
    }

    private function erroPadrao($mensagem)
    {
        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', $mensagem);
    }
    private function salvarResgate($ponto)
    {
        $this->api('ponto_cvs:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'ponto_solicitado' => $ponto
            ])
            ->post('/ponto-cvs');
    }
}
