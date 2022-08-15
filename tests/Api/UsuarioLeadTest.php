<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\UsuarioLead\Helper;

final class UsuarioLeadTest extends Tests
{
    private string $idLead;
    private string $cpf;

    public function __construct()
    {
        parent::__construct();
        $this->bodySalvar = $this->criarBodyLead();
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
        $this->idLead = array_key_exists('dado', $resposta) ? $resposta['dado']['id'] : '';
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
            ->body($body)->post('/usuario-lead');

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
            ->body($body)->post('/usuario-lead');

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
            ->body($body)->post('/usuario-lead');

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
            ->parametro([
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
            ->parametro([
                'pagina' => 1,
                'pesquisa' => $this->bodySalvar['cpf'],
                'nome' => $this->bodySalvar['nome'],
                'email' => $this->bodySalvar['email_pessoal'],
                'cpf' => $this->bodySalvar['cpf'],
                'status' => 'novo',
                'ordem' => 'mais-novo'
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
            ->parametro([
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
            ->parametro([
                'pagina' => 1,
                'ordem' => 'nao_existe'
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
            ])->put('/usuario-lead/' . $this->idLead)->status();

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
        $this->cpf = $this->cpf();
        $resposta = $this
            ->Curl
            ->loginPainel()
            ->body([
                'nome' => $this->nomeCompleto(),
                'email_pessoal' => $this->email(),
                'telefone_pessoal' => $this->telefone(),
                'cpf' => $this->cpf,
                'termo_aceitar' => $this->hoje(),
                'termo_lgpd' => $this->hoje()
            ])
            ->post('/usuario-lead')->array();

        $this->idLead = array_key_exists('dado', $resposta) ? $resposta['dado']['id'] : '';

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
            ->parametro([
                'pagina' => 1,
                'cpf' => $this->cpf
            ])
            ->get('/usuario-cliente')->array();

        $cpf =  array_key_exists('dado', $dado) &&
            array_key_exists('lista', $dado['dado']) &&
            array_key_exists(0, $dado['dado']['lista']) ? $dado['dado']['lista'][0]['cpf'] : '';

        return $this
            ->checkStatus(200)
            ->checkIgual($this->cpf, $cpf);
    }

    public function naoPodeMudarStatusAposSalvarComoCadastroRealizadoTest()
    {
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
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
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
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
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
        $estado = $this->estado();
        return $this->cryptEncode([
            'nome' => $this->nomeCompleto(),
            'email_trabalho' => $this->email(),
            'email_pessoal' => $this->email(),
            'email_funcional' => $this->email(),
            'telefone_pessoal' => $this->telefone(),
            'telefone_trabalho' => $this->telefone(),
            'cpf' => $this->cpf(),
            'rg' => $this->rg(),
            'siape' => $this->numero(100000, 999999),
            'genero' => $this->genero(),
            'data_nascimento' => $this->dataPassada(),
            'trabalho_empresa' => 'marktclub',
            'trabalho_cargo' => 'desenvolvedor',
            'trabalho_data_inicio' => $this->dataPassada(),
            'endereco_cep' => $this->cep(),
            'endereco_logradouro' => $this->logradouro(),
            'endereco_numero' => $this->numero(),
            'endereco_complemento' => $this->complemento(),
            'endereco_bairro' => $this->bairro(),
            'endereco_cidade' => $this->cidade($estado),
            'endereco_estado' => $estado,
            'termo_aceitar' => $this->hoje(),
            'termo_lgpd' => $this->hoje(),
            'lista_dependente' => []
        ], Helper::CRIPTOGRAFAR);
    }
}
