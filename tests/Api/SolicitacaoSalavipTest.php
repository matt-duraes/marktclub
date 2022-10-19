<?php


namespace Tests\Api;

use Tests\Tests;

final class SolicitacaoSalavipTest extends Tests
{
    public function naoPodeSalvarSalavipTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->post('/solicitacao-salavip');

        return $this
            ->checkStatus(404);
    }

    public function listarTodosOsVouchersTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json(['pagina' => 1])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(200);
    }

    public function naoPodeListarEmpresaQueNaoExisteTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'empresa' => 'nao_existe'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A empresa informada não é válida.');
    }
    public function naoPodeListarDataDeInvalidaTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_de' => '10/10/2000'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A data de início da busca não é válida.');
    }
    public function naoPodeListarOrdemInvalidaTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem' => 'nao_existe'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A ordem informada não é válida.');
    }
    public function naoPodeListarDataAteInvalidaTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'data_ate' => '10/10/2000'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A data de final da busca não é válida.');
    }
    public function listarSalavipComTodosOsFiltrosTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'empresa' => 'anafe',
                'data_de' => '2000-01-01',
                'data_ate' => hoje(),
                'ordem' => 'mais-novo'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function deveBuscarEmpresaAnafeTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'empresa' => 'anafe'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('dado.lista.0.empresa', 'ANAFE');
    }
    public function deveBuscarEmpresaAnapeTest()
    {
        $this->api('solicitacao_salavip:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'empresa' => 'anape'
            ])
            ->get('/solicitacao-salavip');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('dado.lista.0.empresa', 'ANAPE');
    }

    public function pegarDadosParaDownloadTest()
    {
        $this->api('solicitacao_salavip:download');

        $this
            ->Curl
            ->loginPainel()
            ->body([
                'campo' => ['empresa', 'codigo', 'data'],
                'ordem' => 'mais-novo'
            ])
            ->post('/solicitacao-salavip/download');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }
}
