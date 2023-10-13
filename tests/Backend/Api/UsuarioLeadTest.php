<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\UsuarioLead\Helper;

final class UsuarioLeadTest extends Tests
{
    private string $idLead;
    private string $cpf;
    private array $bodySalvar;

    public function __construct()
    {
        parent::__construct();
        $this->bodySalvar = $this->criarBodyLead();
        $this->cpf = cpfAleatorio();
    }

    public function verificarSeEstaSalvandoLeadTest()
    {
        $this->api('usuario_lead:salvar');
        $this->bodySalvar = $this->criarBodyLead();

        $this
            ->Curl
            ->loginPainel()
            ->body($this->bodySalvar)->post('/usuario-lead');

        $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');

        $resposta = $this->Curl->array();
        $this->idLead = $resposta['dado']['id'] ?? 'sem-id';
        return $this;
    }

    public function buscarLeadQueFoiSalvoTest()
    {
        $this->api('usuario_lead:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id')
            ->checkRespostaDadoIgual($this->bodySalvar, false, Helper::CRIPTOGRAFAR);
    }

    public function naoPodeSalvarLeadComTermoComDataPassadaTest()
    {
        $this->api('usuario_lead:salvar');
        $body = $this->criarBodyLead();
        $body['termo_aceitar'] = $this->dataPassada();

        $this
            ->Curl
            ->loginPainel()
            ->body($body)->post('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.codigo', 931)
            ->checkIndiceNaoExiste('dado.id');
    }

    public function naoPodeSalvarLeadComTermoComDataFuturaTest()
    {
        $this->api('usuario_lead:salvar');
        $body = $this->criarBodyLead();
        $body['termo_aceitar'] = $this->dataFutura();

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.codigo', 931)
            ->checkIndiceNaoExiste('dado.id');
    }

    public function naoPodeSalvarLeadComLgpdComDataPassadaTest()
    {
        $this->api('usuario_lead:salvar');
        $body = $this->criarBodyLead();
        $body['termo_lgpd'] = $this->dataPassada();

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.codigo', 931)
            ->checkIndiceNaoExiste('dado.id');
    }

    public function naoPodeSalvarLeadComLgpdComDataFuturaTest()
    {
        $this->api('usuario_lead:salvar');
        $body = $this->criarBodyLead();
        $body['termo_lgpd'] = $this->dataFutura();

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.codigo', 931)
            ->checkIndiceNaoExiste('dado.id');
    }

    public function listarTodosOsLeadsTest()
    {
        $this->api('usuario_lead:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
            ])->get('/usuario-lead');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarLeadsComTodosOsFiltrosTest()
    {
        $this->api('usuario_lead:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'   => 1,
                'pesquisa' => $this->bodySalvar['cpf'],
                'nome'     => $this->bodySalvar['nome'],
                'email'    => $this->bodySalvar['email_pessoal'],
                'cpf'      => $this->bodySalvar['cpf'],
                'status'   => 'novo',
                'ordem'    => 'mais-novo'
            ])
            ->get('/usuario-lead');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeListarLeadsComUmStatusInvalidoTest()
    {
        $this->api('usuario_lead:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'nao_existe'
            ])
            ->get('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O Status informado não é um valor válido.');
    }

    public function naoPodeListarLeadsComUmaOrdemInvalidaTest()
    {
        $this->api('usuario_lead:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem'  => 'nao_existe'
            ])
            ->get('/usuario-lead');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A ordem informada não é um valor válido.');
    }

    public function leadNovoNaoPodeMudarStatusParaSemInteresseTest()
    {
        $this->api('usuario_lead:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'sem-interesse'
            ])->put('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Um Lead novo só pode mudar de status para "andamento".');
    }

    public function leadNovoNaoPodeMudarStatusParaCadastroRealizadoTest()
    {
        $this->api('usuario_lead:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'cadastro-realizado'
            ])->put('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Um Lead novo só pode mudar de status para "andamento".');
    }

    public function mudarStatusDeNovoParaEmAndamentoTest()
    {
        return $this->mudarStatusDoLead('andamento');
    }

    public function mudarStatusDeEmAndamentoParaSemInteresseTest()
    {
        return $this->mudarStatusDoLead('sem-interesse');
    }

    private function mudarStatusDoLead($status)
    {
        $this->api('usuario_lead:atualizar');
        $respostaStatus = $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => $status
            ])->put('/usuario-lead/' . $this->idLead)
            ->status();

        $this->api('usuario_lead:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-lead/' . $this->idLead);

        return $this
            ->checkIgual($respostaStatus, 204)
            ->checkStatus(200)
            ->checkIndiceIgual('dado.status', $status);
    }

    public function naoPodeMudarStatusAposSalvarComoSemInteresseTest()
    {
        $this->api('usuario_lead:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'andamento',
            ])->put('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você não pode mudar o status de um Lead finalizado.');
    }

    public function deletarLeadQueFoiSalvoTest()
    {
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(204);
    }

    public function salvarUsuarioNovoDepoisMudarStatusParaCadastradoTest()
    {
        $dado = $this->cryptEncode([
            'nome'             => nomeCompletoAleatorio(),
            'email_pessoal'    => emailAleatorio(),
            'telefone_pessoal' => telefoneAleatorio(),
            'cpf'              => $this->cpf,
            'termo_aceitar'    => hoje(),
            'termo_lgpd'       => hoje()
        ], Helper::CRIPTOGRAFAR);

        $resposta = $this
            ->Curl
            ->loginPainel()
            ->body($dado)
            ->post('/usuario-lead')->array();

        $this->idLead = $resposta['dado']['id'] ?? 'sem-id';

        $this
            ->Curl
            ->body([
                'status' => 'andamento'
            ])->put('/usuario-lead/' . $this->idLead);

        $status = $this
            ->Curl
            ->body([
                'status' => 'cadastro-realizado'
            ])->put('/usuario-lead/' . $this->idLead)->status();

        $this
            ->Curl
            ->get('/usuario-lead/' . $this->idLead);

        return $this
            ->checkIgual($status, 204)
            ->checkIndiceIgual('dado.status', 'cadastro-realizado');
    }

    public function verificarSeUsuarioClienteFoiSalvoPeloLeadTest()
    {
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1,
                'cpf'    => $this->cryptEncode($this->cpf)
            ])
            ->get('/usuario-cliente')->array();

        $cpf = $dado['dado']['lista'][0]['cpf'] ?? 'sem-id';

        return $this
            ->checkStatus(200)
            ->checkIgual($this->cpf, $this->cryptDecode($cpf));
    }

    public function naoPodeMudarStatusAposSalvarComoCadastroRealizadoTest()
    {
        $this->api('usuario_lead:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'andamento'
            ])->put('/usuario-lead/' . $this->idLead);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você não pode mudar o status de um Lead finalizado.');
    }

    public function naoPodeAcharLeadQueFoiDeletadoTest()
    {
        $status = $this
            ->Curl
            ->delete('/usuario-lead/' . $this->idLead)->status();

        $this
            ->Curl
            ->get('/usuario-lead/' . $this->idLead);

        return $this
            ->checkIgual($status, 204)
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    public function naoPodeBuscarLeadPeloIdTest()
    {
        $this->api('usuario_lead:listar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-lead/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }

    public function naoPodeAtualizarLeadPeloIdTest()
    {
        $this->api('usuario_lead:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['status' => 'andamento'])
            ->put('/usuario-lead/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }

    public function naoPodeDeletarLeadPeloIdTest()
    {
        $this->api('usuario_lead:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-cliente/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function criarBodyLead()
    {
        return $this->cryptEncode([
            'nome'                 => nomeCompletoAleatorio(),
            'email_trabalho'       => emailAleatorio(),
            'email_pessoal'        => emailAleatorio(),
            'email_funcional'      => emailAleatorio(),
            'telefone_pessoal'     => telefoneAleatorio(),
            'telefone_trabalho'    => telefoneAleatorio(),
            'cpf'                  => cpfAleatorio(),
            'rg'                   => rgAleatorio(),
            'siape'                => numeroAleatorio(100000, 999999),
            'genero'               => generoAleatorio(),
            'data_nascimento'      => dataPassadaAleatorio(),
            'trabalho_empresa'     => 'marktclub',
            'trabalho_cargo'       => 'desenvolvedor',
            'trabalho_data_inicio' => dataPassadaAleatorio(),
            'endereco_cep'         => '69055695',
            'endereco_logradouro'  => logradouroAleatorio(),
            'endereco_numero'      => numeroAleatorio(),
            'endereco_complemento' => complementoAleatorio(),
            'endereco_bairro'      => bairroAleatorio(),
            'endereco_cidade'      => cidadeAleatorio(),
            'endereco_estado'      => estadoAleatorio(),
            'termo_aceitar'        => hoje(),
            'termo_lgpd'           => hoje(),
            'lista_dependente'     => []
        ], Helper::CRIPTOGRAFAR);
    }
}
