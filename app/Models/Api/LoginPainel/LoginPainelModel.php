<?php

namespace App\Models\Api\LoginPainel;

use stdClass;
use App\Classes\ApiApp\Scope;
use App\Classes\ApiApp\Audience;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Classes\ApiToken\Tipo as ApiTokenTipo;
use App\Models\Api\ComercialEmpresa\HelperModel;
use App\Models\Api\LoginPainel\Trait\MensagemTrait;
use App\Classes\Painel\Config\Padrao as ConfigPadrao;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Classes\ComercialEmpresa\Status as StatusEmpresa;

final class LoginPainelModel
{
    use MensagemTrait;

    private stdClass $Usuario;
    private array $permissaoConfig = [];
    private array $scope = [];
    private array $payload = [];
    public array $token = [];

    public function __construct(
        private string $login,
        private string $senha,
        private string $audience,
        private string $redirectUri,
        private string $state
    ) {
        $this->fazerLogin();
        $this->validarEmpresa();
        $this->pegarPermissao();
        $this->limparPermissaoUsuario();
        $this->pegarScope();
        $this->criarPayload();
        $this->criarToken();
    }

    private function fazerLogin()
    {
        $this->Usuario = (new PegarUsuarioModel(
            login: $this->login,
            senha: $this->senha
        ))->retorno;
    }

    private function validarEmpresa()
    {
        $status = (new HelperModel(true))->pegarCampoPor('status', where: ['id', $this->Usuario->id_admin_empresa]);
        if ((new StatusEmpresa($status))->indice() !== StatusEmpresa::ATIVO) {
            $this->erroLogin('O status da empresa não é ativa.');
        }
    }

    private function pegarPermissao()
    {
        $Painel = new ConfiguracaoEntity();
        try {
            $Painel->buscar([
                'id_admin_empresa', $this->Usuario->id_admin_empresa
            ]);
        } catch (\Throwable) {
            $Painel->buscar([
                'id_admin_empresa', 0
            ]);
        }
        if (empty($Painel->permissao)) {
            $this->erroLogin('Permissao do config não existe ou está vazia.');
        }
        $this->permissaoConfig = $Painel->permissao;
    }

    private function limparPermissaoUsuario()
    {
        $this->Usuario->permissao = array_intersect($this->permissaoConfig, $this->Usuario->permissao);
    }

    private function pegarScope()
    {
        $Config = new ConfigPadrao();
        $scope = $Config->scope($this->Usuario->permissao);
        if (empty($scope)) {
            $this->erroLogin('O Scope do usuário está vazio ao filtrar pelas permissões.');
        }
        $this->scope = array_merge(Scope::PAINEL_SCOPE_PADRAO, $scope);
    }

    private function criarPayload()
    {
        $this->payload = (new PayloadModel($this->Usuario, Audience::PAINEL))->payload;
    }

    private function criarToken(): void
    {
        $Token = new TokenAuthorizationEntity();

        $token = $Token->criarToken(
            (new AppPainelModel())->App,
            $this->payload,
            $this->scope,
            $this->audience,
            $this->redirectUri,
            $this->state,
            $this->Usuario->id_admin_empresa,
            new ApiTokenTipo(ApiTokenTipo::PAINEL),
        );
        if (empty($token)) {
            $this->erroLogin('Erro ao criar o token');
        }
        $this->token = $token;
    }
}
