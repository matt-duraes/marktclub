<?php

namespace App\Models\Api\LoginClube\Cemecard;

use stdClass;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Helpers\CurlHelper;
use App\Classes\UsuarioCliente\Status;
use App\Classes\LoginClube\PegarClienteTrait;
use App\Models\Api\LoginClube\LoginPadraoModel;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;
use App\Models\Api\LoginClube\Trait\ValidarDadoNormalTrait;

class LoginModel extends LoginPadraoModel
{
    use PegarClienteTrait;
    use ValidarDadoNormalTrait;

    public stdClass $Usuario;
    public string $linkAutenticacao;
    private stdClass $dado;
    private int $idUsuario;

    /**
     * @param string|null $login
     * @param string|null $senha
     * @param int|null    $empresa
     * @param Botao       $cadastro
     * @param Botao       $termo
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?string $login = null,
        private readonly ?string $senha = null,
        private readonly ?int $empresa = null,
        private readonly Botao $cadastro = new Botao(),
        private readonly Botao $termo = new Botao()
    ) {
        $this->linkAutenticacao = 'https://api.cemecard.com.br/api/auth/login';
        $this->buscarUsuarioPeloLoginSenha();
        $this->buscarUsuarioNaBase();
        $this->buscarUsuario();
    }

    /**
     * @throws Excecao
     */
    protected function buscarUsuarioPeloLoginSenha(): void
    {

        $Curl = (new CurlHelper())
            ->header([
                'Content-Type' => 'application/json'
            ])
            ->json([
                'document' => $this->login,
                'password' => $this->senha
            ])
            ->post($this->linkAutenticacao);
        $status = $Curl->status();
        $dado = $Curl->object();

        if (
            $status !== 200
            || (!is_object($dado) || !object_key_exists('email', $dado))
        ) {
            $this->usuarioNaoEncontrado();
            return;
        }
        $this->dado = $dado;
    }

    /**
     * @throws Excecao
     */
    private function buscarUsuarioNaBase(): void
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
        $Usuario->status = new Status(Status::ATIVO);
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

    private function buscarUsuario(): void
    {
        $dado = $this->Usuario = $this->pegarCliente([
            ['id', $this->idUsuario]
        ]);
    }
}
