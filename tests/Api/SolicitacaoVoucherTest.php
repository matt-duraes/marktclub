<?php


namespace Tests\Api;

use Tests\Tests;

final class SolicitacaoVoucherTest extends Tests
{
    private ?string $idVoucher;

    public function listarTodosOsVouchersTest()
    {
        $this->api('solicitacao_voucher:listar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->json(['pagina' => 1])
            ->get('/solicitacao-voucher');

        $this->idVoucher = $dado->object()->dado->lista[0]->id ?? null;

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function naoPodeBuscarComUmaDataCriacaoDeInvalidaTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_criacao_de' => '01/01/2000',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de criação de início não está no formato válido.');
    }
    public function naoPodeBuscarComUmaDataCriacaoAteInvalidaTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_criacao_ate' => '01/01/2000',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de criação final não está no formato válido.');
    }
    public function naoPodeBuscarComUmaDataValidacaoDeInvalidaTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_validacao_de' => '01/01/2000',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de validação de início não está no formato válido.');
    }
    public function naoPodeBuscarComUmaDataValidacaoAteInvalidaTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_validacao_ate' => '01/01/2000',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de validação final não está no formato válido.');
    }
    public function naoPodeBuscarStatusInvalidoTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'nao_existe',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O Status informado não é válido.');
    }
    public function naoPodeBuscarOrdemInvalidaTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem' => 'nao_existe',
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A ordem informada não é válida.');
    }
    public function listarTodosOsVouchersComTodosOsFiltrosTest()
    {
        $this->api('solicitacao_voucher:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_criacao_de' => '2000-01-01',
                'data_criacao_ate' => hoje(),
                'data_validacao_de' => '2000-01-01',
                'data_validacao_ate' => hoje(),
                'ordem' => 'mais-velho',
                'status' => 'criado'
            ])
            ->get('/solicitacao-voucher');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.lista.0.status', 'criado');
    }
    public function pegarDetalheDoVoucherTest()
    {
        $this->api('solicitacao_voucher:buscar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->get('/solicitacao-voucher/' . $this->idVoucher);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id');
    }
    public function naoPodePegarVoucherPeloIdTest()
    {
        $this->api('solicitacao_voucher:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/solicitacao-voucher/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro');
    }
}
