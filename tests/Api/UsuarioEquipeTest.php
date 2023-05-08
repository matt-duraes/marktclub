<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\UsuarioEquipe\Helper;

final class UsuarioEquipeTest extends Tests
{
    private string $idEmpresaMarktclub = '14afa776394ada4be23be6acf7e3259e';
    private string $idEmpresaAnafe = '0ffc5c56b99f81ca0edea8bdf524b688';
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
        $this->tabela(TABELA_USUARIO_EQUIPE)->resetar();
    }

    public function verificarSeEstaSalvandoUsuarioTest()
    {
        $this->api('usuario_equipe:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->bodySalvar)
            ->post('/usuario-equipe');

        $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');

        $resposta = $this->Curl->array();
        $this->idUsuario = array_key_exists('dado', $resposta) ? $resposta['dado']['id'] : '';
        return $this;
    }
    public function buscarUsuarioQueFoiSalvoTest()
    {
        $this->api('usuario_equipe:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-equipe/' . $this->idUsuario);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idUsuario);
    }
    public function naoPodeAtualizarCpfDeUmUsuarioQueJaTemCpfTest()
    {
        $this->api('usuario_equipe:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'cpf' => $this->cryptEncode($this->cpf())
            ])
            ->put('/usuario-equipe/' . $this->idUsuario);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Você não pode mudar o CPF desse usuário.');
    }
    public function naoPodeSalvarUmUsuarioComCpfDuplicadoTest()
    {
        $this->api('usuario_equipe:salvar');

        $body = $this->cryptEncode([
            'nome' => $this->nomeCompleto(),
            'cpf' => $this->bodySalvar['cpf'],
            'email_pessoal' => $this->email(),
            'status' => 'inativo',
        ], lista: ['nome', 'email_pessoal', 'status']);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)->post('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O CPF informado já está em uso por outro usuário.');
    }


    public function naoPodeSalvarUmUsuarioSemCpfTest()
    {
        $this->api('usuario_equipe:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->criarBodyUsuario(['nome', 'email_trabalho', 'status']))
            ->post('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo CPF não pode ser vazio.');
    }
    public function naoPodeSalvarUmUsuarioSemEmailTrabalhoTest()
    {
        $this->api('usuario_equipe:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->criarBodyUsuario(['nome', 'cpf', 'status']))->post('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo E-mail de trabalho não pode ser vazio.');
    }

    public function listarTodosOsUsuariosTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1
            ])
            ->get('/usuario-equipe');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function listarUsuarioComTodosOsFiltrosTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'quantidade' => 20,
                'empresa' => $this->idEmpresaMarktclub,
                'pesquisa' => $this->bodySalvar['cpf'],
                'nome' => $this->bodySalvar['nome'],
                'email' => $this->bodySalvar['email_pessoal'],
                'cpf' => $this->bodySalvar['cpf'],
                'status' => $this->bodySalvar['status'],
                'ordem' => 'mais-novo'
            ])
            ->get('/usuario-equipe');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeListarComPaginaInvalidaTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 'nao-existe'
            ])
            ->get('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo pagina está inválido.');
    }
    public function naoPodeListarComOrdemInvalidaTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'ordem' => 'nao-existe'
            ])
            ->get('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A ordem informada não é um valor válido.');
    }
    public function naoPodeListarUsuarioComUmStatusInvalidoTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'nao_existe'
            ])
            ->get('/usuario-equipe');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O Status informado não é um valor válido.');
    }
    public function deletarUsuarioBuscadoTest()
    {
        $this->api('usuario_equipe:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-equipe/' . $this->idUsuario);

        return $this
            ->checkStatus(204);
    }
    public function naoPodeAcharUsuarioDeletadoTest()
    {
        $this->api('usuario_equipe:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-equipe/' . $this->idUsuario);

        return $this
            ->checkStatus(404);
    }
    public function naoPodeBuscarUsuarioPeloIdTest()
    {
        $this->api('usuario_equipe:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-equipe/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }
    public function naoPodeAtualizarUsuarioPeloIdTest()
    {
        $this->api('usuario_equipe:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['nome' => $this->cryptEncode($this->nomeCompleto())])
            ->put('/usuario-equipe/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }
    public function naoPodeDeletarUsuarioPeloIdTest()
    {
        $this->api('usuario_equipe:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-equipe/1');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Você deve enviar um COD ou UUID para fazer a busca.');
    }
    public function mudarEmpresaDoUsuarioTest()
    {
        $this->api('usuario_equipe:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['empresa' => $this->idEmpresaAnafe])
            ->put('/usuario-equipe/empresa');

        return $this
            ->checkStatus(204);
    }
    public function naoPodeMudarEmpresaDoUsuarioParaUmaEmpresaInvalidaTest()
    {
        $this->api('usuario_equipe:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body(['empresa' => 'nao-existe'])
            ->put('/usuario-equipe/empresa');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Nenhuma empresa encontrada.');
    }
    public function naoPodeMudarEmpresaDoUsuarioComCredentialTokenTest()
    {
        $this->api('usuario_equipe:atualizar');
        $this
            ->Curl
            ->body(['empresa' => 'nao-existe'])
            ->put('/usuario-equipe/empresa');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual('erro.mensagem', 'Nenhum usuário encontrado.');
    }
    public function listarTodosOsPerfilTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->get('/usuario-equipe/perfil');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function crairSelectTodosUsuarioTest()
    {
        $this->api('usuario_equipe:listar');
        $this
            ->Curl
            ->json(['titulo' => 'Teste'])
            ->get('/usuario-equipe/select');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function verificarSeEstaValidandoSenhaTest()
    {
        $this->api('usuario_equipe:validar_senha');
        $this
            ->Curl
            ->loginPainel()
            ->body(['senha' => $this->cryptEncode('Teste@1324')])
            ->post('/usuario-equipe/validar-senha');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('dado.senha', 1);
    }
    public function verificarSeEstaRetornandoErroAoValidarSenhaErradaTest()
    {
        $this->api('usuario_equipe:validar_senha');
        $this
            ->Curl
            ->loginPainel()
            ->body(['senha' => $this->cryptEncode('SenhaErrada')])
            ->post('/usuario-equipe/validar-senha');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Verifique a senha digitada e tente novamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function criarBodyUsuario(array $campo = [])
    {
        $completo = [
            'nome' => $this->nomeCompleto(),
            'cpf' => $this->cpf(),
            'genero' => $this->genero(),
            'data_nascimento' => $this->dataPassada(),
            'email_trabalho' => $this->email(),
            'email_pessoal' => $this->email(),
            'telefone_trabalho' => $this->telefone(),
            'telefone_pessoal' => $this->telefone(),
            'permissao' => ["solicitacao_voucher_index"],
            'senha' => $this->senha('Teste@1324'),
            'status' => 'inativo',
            'primeiro_acesso' => 'sim',
            'mudar_senha' => 'sim',
            'empresa' => $this->idEmpresaMarktclub
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
