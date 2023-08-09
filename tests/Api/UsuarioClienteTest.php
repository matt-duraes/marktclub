<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\UsuarioCliente\Helper;

final class UsuarioClienteTest extends Tests
{
    private string $idUsuario;
    private array $bodySalvar;

    public function __construct()
    {
        parent::__construct();
        $this->finalizarTeste();
        $this->bodySalvar = $this->criarBodyUsuario();
    }

    public function finalizarTeste()
    {
        $this
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->tabela(TABELA_USUARIO_GRUPO)
            ->resetar();
    }

    public function verificarSeEstaSalvandoUsuarioTest()
    {
        $this->api('usuario_cliente:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->bodySalvar)->post('/usuario-cliente');

        $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');

        $resposta = $this->Curl->array();
        $this->idUsuario = array_key_exists('dado', $resposta) ? $resposta['dado']['id'] : '';
        return $this;
    }

    public function buscarUsuarioQueFoiSalvoTest()
    {
        $this->api('usuario_cliente:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-cliente/' . $this->idUsuario);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id')
            ->checkRespostaDadoIgual($this->bodySalvar, false, Helper::CRIPTOGRAFAR);
    }

    public function naoPodeAtualizarCpfDeUmUsuarioQueJaTemCpfTest()
    {
        $this->api('usuario_cliente:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'cpf' => $this->cryptEncode($this->cpf())
            ])
            ->put('/usuario-cliente/' . $this->idUsuario);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Você não pode mudar o CPF desse usuário.');
    }

    public function naoPodeSalvarUmUsuarioComCpfDuplicadoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'          => $this->nomeCompleto(),
            'cpf'           => $this->bodySalvar['cpf'],
            'email_pessoal' => $this->email(),
            'status'        => 'inativo',
        ], lista: ['nome', 'email_pessoal', 'status']);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O CPF informado já está em uso por outro usuário.');
    }

    public function naoPodeSalvarUmUsuarioComEmailPessoalDuplicadoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'          => $this->nomeCompleto(),
            'cpf'           => $this->cpf(),
            'email_pessoal' => $this->bodySalvar['email_pessoal'],
            'status'        => 'inativo',
        ], ['nome', 'cpf', 'status']);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O E-mail pessoal informado já está em uso por outro usuário.');
    }

    public function naoPodeSalvarUmUsuarioComEmailTrabalhoDuplicadoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'           => $this->nomeCompleto(),
            'cpf'            => $this->cpf(),
            'email_trabalho' => $this->bodySalvar['email_trabalho'],
            'status'         => 'inativo',
        ], ['nome', 'cpf', 'status']);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O E-mail de trabalho informado já está em uso por outro usuário.');
    }

    public function naoPodeSalvarUmUsuarioSemCpfTest()
    {
        $this->api('usuario_cliente:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->criarBodyUsuario(['nome', 'email_trabalho', 'status']))
            ->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo CPF é obrigatório.');
    }

    public function naoPodeSalvarUmUsuarioSemEmailTest()
    {
        $this->api('usuario_cliente:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->criarBodyUsuario(['nome', 'cpf', 'status']))->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar pelo menos um e-mail para salvar.');
    }

    public function naoPodeSalvarUmUsuarioComGrupoInvalidoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'           => $this->nomeCompleto(),
            'cpf'            => $this->cpf(),
            'email_trabalho' => $this->email(),
            'grupo'          => 'grupo_invalido',
            'status'         => 'inativo',
        ], Helper::CRIPTOGRAFAR);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O grupo informado não é um valor válido.');
    }

    public function naoPodeSalvarUmUsuarioComTrabalhoEmpresaInvalidoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'             => $this->nomeCompleto(),
            'cpf'              => $this->cpf(),
            'email_trabalho'   => $this->email(),
            'trabalho_empresa' => 'nome_invalido',
            'status'           => 'inativo',
        ], Helper::CRIPTOGRAFAR);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Empresa que trabalha não é um valor válido.');
    }

    public function naoPodeSalvarUmUsuarioComTrabalhoCargoInvalidoTest()
    {
        $this->api('usuario_cliente:salvar');

        $body = $this->cryptEncode([
            'nome'           => $this->nomeCompleto(),
            'cpf'            => $this->cpf(),
            'email_trabalho' => $this->email(),
            'trabalho_cargo' => 'nome_invalido',
            'status'         => 'inativo',
        ], Helper::CRIPTOGRAFAR);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Cargo na empresa não é um valor válido.');
    }

    public function listarTodosOsUsuariosTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarUsuarioComTodosOsFiltrosTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'           => 1,
                'pesquisa'         => $this->bodySalvar['cpf'],
                'nome'             => $this->bodySalvar['nome'],
                'email'            => $this->bodySalvar['email_pessoal'],
                'cpf'              => $this->bodySalvar['cpf'],
                'data_upload'      => $this->hoje(),
                'data_criacao_de'  => $this->dataPassada(),
                'data_criacao_ate' => $this->hoje(),
                'matricula'        => $this->bodySalvar['matricula'],
                'status'           => $this->bodySalvar['status'],
                'ordem'            => 'mais-novo'
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeListarComDataUploadInvalidaTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'      => 1,
                'data_upload' => '10/10/2022',
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de upload informado não é um valor válido.');
    }

    public function naoPodeListarComDataCriacaoInicialInvalidaTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'          => 1,
                'data_criacao_de' => '10/10/2022',
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de criação do começo informado não é um valor válido.');
    }

    public function naoPodeListarComDataCriacaoFinalInvalidaTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'           => 1,
                'data_criacao_ate' => '10/10/2022',
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de criação final informado não é um valor válido.');
    }

    public function naoPodeListarComPaginaInvalidaTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 'nao-existe'
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A página deve ser um número inteiro.');
    }

    public function naoPodeListarComOrdemInvalidaTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem'  => 'nao-existe'
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A ordem informada não é um valor válido.');
    }

    public function naoPodeListarUsuarioComUmStatusInvalidoTest()
    {
        $this->api('usuario_cliente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'nao_existe'
            ])
            ->get('/usuario-cliente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O Status informado não é um valor válido.');
    }

    public function deletarUsuarioBuscadoTest()
    {
        $this->api('usuario_cliente:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-cliente/' . $this->idUsuario);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAcharUsuarioDeletadoTest()
    {
        $this->api('usuario_cliente:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-cliente/' . $this->idUsuario);

        return $this
            ->checkStatus(404);
    }

    public function naoPodeBuscarUsuarioPeloIdTest()
    {
        $this->api('usuario_cliente:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-cliente/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    public function naoPodeAtualizarUsuarioPeloIdTest()
    {
        $this->api('usuario_cliente:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['nome' => $this->cryptEncode($this->nomeCompleto())])
            ->put('/usuario-cliente/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }

    public function naoPodeDeletarUsuarioPeloIdTest()
    {
        $this->api('usuario_cliente:deletar');
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
    private function criarBodyUsuario(array $campo = [])
    {
        $estado = $this->estado();
        $completo = [
            'nome'                 => $this->nomeCompleto(),
            'cpf'                  => $this->cpf(),
            'matricula'            => $this->numero(100000, 999999),
            'siape'                => $this->numero(100000, 999999),
            'genero'               => $this->genero(),
            'data_nascimento'      => $this->dataPassada(),
            'email_trabalho'       => $this->email(),
            'email_pessoal'        => $this->email(),
            'telefone_trabalho'    => $this->telefoneFixo(),
            'telefone_pessoal'     => $this->telefoneCelular(),
            'senha'                => $this->senha(),
            'primeiro_acesso'      => $this->simNao(),
            'mudar_senha'          => $this->simNao(),
            'estado_civil'         => $this->estadoCivil(),
            'endereco_cep'         => $this->cep(),
            'endereco_logradouro'  => $this->logradouro(),
            'endereco_numero'      => $this->numero(),
            'endereco_complemento' => $this->complemento(),
            'endereco_bairro'      => $this->bairro(),
            'endereco_estado'      => $estado,
            'endereco_cidade'      => $this->cidade($estado),
            'situacao'             => $this->random(['ativo', 'aposentado']),
            'trabalho_empresa'     => 'marktclub',
            'trabalho_cargo'       => 'desenvolvedor',
            'tipo_pagamento'       => 'cartao-credito',
            'trabalho_data_inicio' => $this->dataPassada(),
            'grupo'                => 'teste-01',
            'status'               => $this->random(['ativo', 'inativo'])
        ];

        if (empty($campo)) {
            return $this->cryptEncode($completo, Helper::CRIPTOGRAFAR);
        }
        $lista = [];
        foreach ($campo as $indice) {
            $lista[$indice] = $completo[$indice];
        }
        return $this->cryptEncode($lista, Helper::CRIPTOGRAFAR);
    }
}
