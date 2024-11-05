<?php

namespace App\Models\Api\LoginClube\EmporioNaval;

use App\Classes\LoginClube\PegarClienteTrait;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\LoginClube\LoginPadraoModel;
use App\Models\Api\LoginClube\Trait\ValidarDadoNormalTrait;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;
use Helpers\CurlHelper;
use Modules\Botao;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\Nome;
use stdClass;

final class LoginModel extends LoginPadraoModel
{
    use PegarClienteTrait;
    use ValidarDadoNormalTrait;

    public stdClass $Usuario;
    public string $linkAutenticacao;
    private stdClass $dado;
    private int $idUsuario;

    public function __construct(
        private ?string $login = null,
        private ?string $senha = null,
        private ?int $empresa = null,
        private Botao $cadastro = new Botao(null),
        private Botao $termo = new Botao(null),
    ) {
        $this->linkAutenticacao = env('EMPORIO_NAVAL_LINK_AUTENTICACAO', '');
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
        $this->buscarUsuarioNaBase();
        $this->buscarUsuario();
    }

    protected function buscarUsuarioPeloLoginSenha(): void
    {
        $Curl = (new CurlHelper())
            ->header([
                'Content-Type' => 'application/json'
            ])
            ->json([
                'cpf' => $this->login,
                'senha' => $this->senha
            ])
            ->post($this->linkAutenticacao);
        $status = $Curl->status();
        $dado = $Curl->object();
        if ($status !== 200 || !is_object($dado) || !object_key_exists('Nome', $dado) || !object_key_exists(
                'Email',
                $dado
            )) {
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
        $Usuario->nome = new Nome($this->dado->Nome);
        $Usuario->email_pessoal = new Email($this->dado->Email);
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
                        'Email' => $this->dado->Email,
                        'Nome'  => $this->dado->Nome,
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
