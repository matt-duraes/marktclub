<?php


namespace Tests\Api;

use Tests\Tests;
use App\Classes\PontoCvs\Helper;

final class PontoCvsTest extends Tests
{
    private array $dadoSalvo;
    private string $idPonto;

    public function __construct()
    {
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
        return $this->erroPadrao('Quantidade de pontos informada é maior que seu saldo atual.');
    }

    public function salvandoSolicitacaoSemErroTest()
    {
        $this->api('ponto_cvs:salvar');
        $Curl = $this
            ->Curl
            ->loginPainel()
            ->body([
                'cpf' => $this->cryptEncode('67783406815'),
                'ponto_solicitado' => Helper::PONTO_MINIMO
            ])
            ->post('/ponto-cvs');

        $dado = $Curl->array();
        $this->dadoSalvo = $dado['dado'] ?? [];
        $this->idPonto = array_key_exists('dado', $dado) ? $dado['dado']['id'] : '';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.ponto_solicitado', Helper::PONTO_MINIMO)
            ->checkIndiceIgual('dado.status', 'solicitado')
            ->checkIndiceIgual('dado.voucher', '')
            ->checkIndiceIgual('dado.data_voucher', '');
    }

    public function naoPodeSolicitarComOutraSolicitacaoAtivaTest()
    {
        $pontoMinimo = Helper::PONTO_MINIMO;

        $this->salvarResgate($pontoMinimo);
        return $this->erroPadrao('Você só pode fazer uma solicitação por vez, aguarde a finalização da solicitação em aberto.');
    }

    public function buscarPontoQueFoiSalvoTest()
    {
        $this->api('ponto_cvs:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/ponto-cvs/' . $this->idPonto);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id');
    }

    public function listarTodosOsPontosTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem' => 'mais-novo',
                'cpf' => $this->cryptEncode('67783406815'),
            ])->get('/ponto-cvs');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarPontosComTodosOsFiltrosTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'quantidade' => 30,
                'status' => 'solicitado',
                'cpf' => $this->cryptEncode('67783406815'),
                'ordem' => 'mais-novo'
            ])
            ->get('/ponto-cvs');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeListarPontosComUmStatusInvalidoTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'nao_existe'
            ])
            ->get('/ponto-cvs');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo status não é um valor válido.');
    }

    public function naoPodeListarPontosComUmaOrdemInvalidaTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem' => 'nao_existe'
            ])
            ->get('/ponto-cvs');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo ordem não é um valor válido.');
    }

    public function naoPodeListarPontosComUmCpfInvalidoTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'cpf' => $this->cryptEncode('nao_existe')
            ])
            ->get('/ponto-cvs');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo cpf não é um valor válido.');
    }

    public function PontoNovoNaoPodeMudarStatusParaAprovadoTest()
    {
        $this->erroMudarStatusDoPonto('aprovado', 'Só é possível mudar o status de "Solicitado" para "Em andamento".');
    }

    public function mudarStatusDeSolicitadoParaEmAndamentoTest()
    {
        return $this->mudarStatusDoPonto('andamento');

        
    }
    public function mudarStatusDeEmAndamentoParaRecusadoTest()
    {
        return $this->mudarStatusDoPonto('recusado');
    }

    public function naoPodeMudarStatusAposSalvarComoRecusadoTest()
    {
        $this->erroMudarStatusDoPonto('andamento', 'Você não pode mudar o status de uma solicitação que foi recusada ou aprovada.');
    }

    public function naoPodeBuscarPontoPeloIdTest()
    {
        $this->api('ponto_cvs:listar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/ponto-cvs/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    public function naoPodeAtualizarPontoPeloIdTest()
    {
        $this->api('ponto_cvs:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['status' => 'andamento'])
            ->put('/ponto-cvs/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */

    private function erroMudarStatusDoPonto($status, $mensagem)
    {
        $this->api('ponto_cvs:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => $status,
            ])->put('/ponto-cvs/' . $this->idPonto);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', $mensagem);
    }

    private function mudarStatusDoPonto($status)
    {
        $this->api('ponto_cvs:atualizar');
        $respostaStatus = $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => $status
            ])->put('/ponto-cvs/' . $this->idPonto)->status();

        $this->api('ponto_cvs:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/ponto-cvs/' . $this->idPonto);

        return $this
            ->checkIgual($respostaStatus, 204)
            ->checkStatus(200)
            ->checkIndiceIgual('dado.status', $status);
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
                'ponto_solicitado' => $ponto,
                'cpf' => $this->cryptEncode('67783406815')
            ])
            ->post('/ponto-cvs');
    }
}
