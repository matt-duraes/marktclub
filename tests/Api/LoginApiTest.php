<?php

namespace Tests\Api;

use stdClass;
use Tests\Tests;
use App\Classes\UsuarioCliente\Helper;

final class LoginApiTest extends Tests
{
    private string $cpf;
    private array $usuario;
    private array $bodySalvar;
    public function __construct()
    {
        parent::__construct();
        $this->bodySalvar = $this->criarBodyUsuario();
    }

    public function fazerLoginComNovoUsuarioTest()
    {
        $this->api('login:api');
        $this
            ->Curl
            ->body($this->bodySalvar)
            ->post('/login/api');

        return $this->loginOk();
    }

    public function fazerLoginComMesmoUsuarioTest()
    {
        $this->api('login:api');
        $this
            ->Curl
            ->body([
                'nome' => $this->cryptEncode($this->nomeCompleto()),
                'email_pessoal' => $this->cryptEncode($this->email()),
                'cpf' => $this->cryptEncode($this->cpf)
            ])
            ->post('/login/api');
        return $this->loginOk();
    }

    public function buscarUsuarioSalvoTest()
    {
        $this->api('usuario_cliente:listar');

        $usuario = $this
            ->Curl
            ->json([
                'pagina' => 1,
                'cpf' => $this->cryptEncode($this->cpf)
            ])
            ->get('/usuario-cliente');

        $this->usuario = $usuario->object()->dado->lista ?? [];

        return $this
            ->checkStatus(200)
            ->checkIgual($this->cpf, $this->cryptDecode($this->usuario[0]->cpf ?? ''));
    }

    public function verificaSeSalvouApenasUmUsuarioTest()
    {
        return $this->checkIgual(1, count($this->usuario));
    }

    public function naoPodeFazerLoginSemCriptografarCpfTest()
    {
        $this->api('login:api');
        $this
            ->Curl
            ->body([
                'nome' => $this->cryptEncode($this->nomeCompleto()),
                'email_pessoal' => $this->cryptEncode($this->email()),
                'cpf' => $this->cpf
            ])
            ->post('/login/api');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O indice cpf não pode ser descriptografado.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function loginOk()
    {
        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.link');
    }

    private function criarBodyUsuario(array $campo = [])
    {
        $estado = $this->estado();
        $this->cpf = $this->cpf();
        $completo = [
            'nome' => $this->nomeCompleto(),
            'cpf' => $this->cpf,
            'matricula' => $this->numero(100000, 999999),
            'siape' => $this->numero(100000, 999999),
            'genero' => $this->genero(),
            'data_nascimento' => $this->dataPassada(),
            'email_trabalho' => $this->email(),
            'email_pessoal' => $this->email(),
            'telefone_trabalho' => $this->telefoneFixo(),
            'telefone_pessoal' => $this->telefoneCelular(),
            'estado_civil' => $this->estadoCivil(),
            'endereco_estado' => $estado,
            'endereco_cidade' => $this->cidade($estado),
            'grupo' => 'teste-01',
            'salavip' => 2
        ];

        return $this->cryptEncode($completo, Helper::CRIPTOGRAFAR);
    }
}
