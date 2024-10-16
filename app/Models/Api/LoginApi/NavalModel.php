<?php

namespace App\Models\Api\LoginApi;

use App\Classes\ApiToken\Tipo as TokenTipo;
use App\Helpers\EmporioNaval\UsuarioHelper;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\ConstrutorClube\ClubeModel;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\LoginApi\Trait\ConstrutorTrait;
use Erro\Excecao;
use Modules\Botao;
use Throwable;

class NavalModel
{
    use PegarAppTrait;
    use ConstrutorTrait;

    public array $token;
    public array $construtor;
    private int $idEmpresa;
    private array $usuarioNaval;
    private string $linkClube;

    /**
     * @param string|null $login
     * @param string|null $senha
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
        //$this->pegarConstrutor();
        $this->criarToken();
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

    /**
     * @return void
     */
    private function criarToken(): void
    {
        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID')]);

        $Token = new TokenAuthorizationEntity();
        $this->token = $Token->criarToken(
            $App,
            ['sub' => $this->usuarioNaval['Id']],
            $App->scope_permitido,
            $App->audience,
            $App->redirect_uri,
            uuid(),
            $this->idEmpresa,
            new TokenTipo(TokenTipo::CLUBE)
        );
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarConstrutor(): void
    {
        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->buscar([
                ['id_admin_empresa', $this->idEmpresa],
                ['status', 'in', [1, 2]]
            ]);
        } catch (Throwable $e) {
            mensagemStatus(404, localhost: 'Erro ao buscar empresa. ' . $e->getMessage());
        }
        $this->idEmpresa = $Construtor->id_admin_empresa;
        $this->construtor = (new ClubeModel($Construtor))->construtor;
    }
}
