<?php

namespace App\Models\Api\LoginClube;

use stdClass;
use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Helpers\CurlHelper;
use App\Classes\LoginClube\PegarClienteTrait;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;

final class LoginClubePoupyModel extends LoginPadraoModel
{
    use PegarClienteTrait;

    public stdClass $Usuario;
    private stdClass $dado;
    private int $idUsuario;
    public string $linkAutenticacao;

    public function __construct(
        private ?string $login = null,
        private ?string $senha = null,
        private ?int $empresa = null,
        private Botao $cadastro = new Botao(null),
        private Botao $termo = new Botao(null),
    ) {
        $this->linkAutenticacao = env('CLUBE_POUPY_LINK_AUTENTICACAO');
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
        $this->buscarUsuarioNaBase();
        $this->buscarUsuario();
    }

    protected function validarDadosDeLogin(): void
    {
        if (empty($this->login)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar sua senha para continuar.');
        } elseif ($this->cadastro->valor() == Botao::SIM && $this->termo->valor() != Botao::SIM) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve aceitar os termo de uso para continuar.');
        }
    }

    protected function buscarUsuarioPeloLoginSenha(): void
    {
        $Curl = (new CurlHelper())
            ->header(['Content-Type' => 'application/json'])
            ->json([
                'cpf'   => $this->login,
                'senha' => $this->senha,
            ])
            ->post($this->linkAutenticacao);

        $status = $Curl->status();
        $dado = $Curl->object();
        if ($status !== 200 || (!is_object($dado) || !object_key_exists('success', $dado) || $dado->success !== true)) {
            $this->usuarioNaoEncontrado();
            return;
        }
        $this->dado = $dado;
    }

    private function buscarUsuarioNaBase()
    {
        $Usuario = new SalvarAtualizarModel(
            empresa: $this->empresa,
            salvar: $this->cadastro->valor() == Botao::SIM,
            atualizar: true
        );
        $Usuario->cpf = new Cpf($this->login);
        $Usuario->nome = new Nome($this->dado->nome);
        $Usuario->email_pessoal = new Email($this->dado->email);
        $Usuario->data_termo = new Data(hoje());
        $Usuario->buscar();

        if ($Usuario->acao == SalvarAtualizarModel::CADASTRAR_USUARIO) {
            mensagemErro(
                titulo: 'Cadastrar Usuário!',
                mensagem: 'Para continuar, aceitar os termo de uso e compartilhamento de dados.',
                status: 403,
                dado: [
                    'cadastro' => 'sim',
                    'dado'     => [
                        'Email' => $this->dado->email,
                        'Nome'  => $this->dado->nome,
                        'CPF'   => strCpf($this->login)
                    ]
                ]
            );
        }
        $this->idUsuario = $Usuario->id;
    }

    private function buscarUsuario()
    {
        $this->Usuario = $this->pegarCliente([
            ['id', $this->idUsuario]
        ]);
    }
}
