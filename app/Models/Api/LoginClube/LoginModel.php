<?php

namespace App\Models\Api\LoginClube;

use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\LoginClube\UsuarioEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

final class LoginModel
{
    private AppEntity $App;
    private UsuarioEntity $Usuario;
    private ConstrutorEntity $Construtor;
    private array $Token;

    public function __construct(
        ?string $login = null,
        ?string $senha = null,
        ?string $google = null,
        ?string $facebook = null,
        ?string $clientId = null,
        ?string $redirectUri = null,
        ?string $state = null,
        ?string $scope = null,
        ?string $audience = null
    ) {
        $this->pegarApp($clientId);
        $this->validarRedirectUri($redirectUri);
        $this->pegarConstrutor();
        $this->buscarUsuario($login, $senha);
        $this->criarToken($state, $scope, $audience, $redirectUri);
    }

    private function pegarApp($clientId)
    {
        $App = new AppEntity();
        try {
            $App->buscar([
                ['client_id', $clientId],
                ['authorization_code', 1],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            mensagemStatus(401, localhost: 'Não foi encontrado um APP para o login.');
        }
        $this->App = $App;
    }

    private function validarRedirectUri($redirectUri)
    {
        if (!in_array($redirectUri, $this->App->redirect_uri)) {
            mensagemStatus(403, localhost: 'O Redirect URI não é valido.');
        }
    }

    private function pegarConstrutor()
    {
        $Construtor = new ConstrutorEntity();
        try {
            $Construtor->buscar([
                ['empresa', $this->App->id_admin_empresa],
                ['status', 'in', [1, 2]]
            ]);
        } catch (\Throwable) {
            mensagemStatus(403, localhost: 'Não foi encontrado um construtor para o login.');
        }
        $this->Construtor = $Construtor;
    }

    private function buscarUsuario($login, $senha)
    {
        $classe = $this->Construtor->classe_login;
        $nomeClasse = 'App\Models\Api\LoginClube\Usuario\\' . $classe . 'Model';

        if (!file_exists(__DIR__ . '/Usuario/' . $classe . 'Model.php') || !class_exists($nomeClasse)) {
            mensagemStatus(500, localhost: 'Não existe o arquivo de login via API.');
        }

        $this->Usuario = (new $nomeClasse)->fazerLogin($login, $senha, $this->App->id_admin_empresa);
    }

    private function criarToken(string $state, string $scope, string $audience, string $redirectUri)
    {
        $Token = new TokenAuthorizationEntity();
        $Usuario = $this->Usuario;
        $Contrustor = $this->Construtor;

        $body = [
            'sub' => $Usuario->id,
            'club' => $Contrustor->id,
            'name' => $Usuario->nome->nome(),
            'email' => $Usuario->email->email(),
            'email_verified' => 'nao',
            'picture' => $Usuario->imagem,
            'create_at' => $Usuario->data_criacao->date(),
            'updated_at' => $Usuario->data_atualizacao->date(),
            'new_access' => $Usuario->primeiro_acesso->valor()
        ];

        $this->Token = $Token->criarToken(
            app: $this->App,
            body: $body,
            scope: empty($scope) ? [] : explode(' ', $scope),
            audience: $audience,
            redirectUri: $redirectUri,
            state: $state,
            logado: true
        );
    }

    public function token()
    {
        return $this->Token;
    }
}
