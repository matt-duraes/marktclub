<?php

namespace App\Models\Api\LoginApi;

use App\Classes\LoginClube\PegarClienteTrait;
use App\Helpers\EmporioNaval\UsuarioHelper;
use App\Models\Api\UsuarioCliente\SalvarAtualizarModel;
use Erro\Excecao;
use Modules\Botao;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\Nome;
use stdClass;

class NavalModel
{
    use PegarClienteTrait;

    public stdClass $Usuario;
    private int $idEmpresa;
    private array $usuarioNaval;
    private string $linkClube;
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
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
        $this->buscarUsuarioNaBase();
        $this->buscarUsuario();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarDadosDeLogin(): void
    {
        if (empty($this->login)) {
            mensagemErro(
                'Campo obrigatório!',
                'Você deve digitar seu login para continuar.'
            );
        } elseif (empty($this->senha)) {
            mensagemErro(
                'Campo obrigatório!',
                'Você deve digitar sua senha para continuar.'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function buscarUsuarioPeloLoginSenha(): void
    {
        $this->usuarioNaval = (new UsuarioHelper())->buscarUsuario($this->login, $this->senha);
        if (empty($this->usuarioNaval)) {
            mensagemErro(
                'Não foi possível realizar o login!',
                'Você deve digitar seu login e senha corretamente.'
            );
        }
    }

    private function buscarUsuario(): void
    {
        $this->Usuario = $this->pegarCliente([
            ['id', $this->idUsuario]
        ]);
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function buscarUsuarioNaBase(): void
    {
        $Usuario = new SalvarAtualizarModel(
            $this->empresa,
            $this->cadastro->valor() == Botao::SIM,
            true
        );
        $Usuario->cpf = new Cpf($this->login);
        $Usuario->nome = new Nome($this->usuarioNaval['nome']);
        $Usuario->email_pessoal = new Email($this->usuarioNaval['email']);
        $Usuario->data_termo = new Data(hoje());
        $Usuario->buscar();

        if ($Usuario->acao == SalvarAtualizarModel::CADASTRAR_USUARIO) {
            mensagemErro(
                'Cadastrar Usuário!',
                'Para continuar, aceitar os termo de uso e compartilhamento de dados.',
                403,
                dado: [
                    'cadastro' => 'sim',
                    'dado'     => [
                        'Email' => $this->usuarioNaval['email'],
                        'Nome'  => $this->usuarioNaval['nome'],
                        'CPF'   => strCpf($this->login)
                    ]
                ]
            );
        }
        $this->idUsuario = $Usuario->id;
    }
}
