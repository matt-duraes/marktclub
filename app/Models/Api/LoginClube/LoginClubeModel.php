<?php

namespace App\Models\Api\LoginClube;

use Http\Request;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\AdminConstrutor\ClubeModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

final class LoginClubeModel
{
    private ClienteEntity $Usuario;
    private int $idEmpresa;
    public array $token;
    public array $construtor;
    private array $listaUriHomologacao;

    /**
     * Faz o login normal do usuário com usuario e senha
     *
     * @param Request $request Request da requisição
     */
    public function __construct(
        private string $login,
        private string $senha,
        private string $redirectUri,
        private string $state
    ) {
        $this->listaUriHomologacao = env('API_REDIRECT_URI_HOMOLOGACAO', []);
        $this->pegarConstrutor();
        $this->fazerLogin();
        $this->criarToken();
    }

    private function pegarConstrutor()
    {
        $redirectUri = explode('/', preg_replace('/^https?\:\/\//', '', $this->redirectUri))[0];
        $this->redirectUri = $redirectUri;
        if (!eProducao() && array_key_exists($redirectUri, $this->listaUriHomologacao)) {
            $redirectUri = $this->listaUriHomologacao[$redirectUri];
        }
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_site', 'like', 'https://' . $redirectUri . '%'],
            ['status', 1]
        ]);
        $this->idEmpresa = $Construtor->id_admin_empresa;
        $this->construtor = (new ClubeModel($Construtor))->construtor;
    }

    private function fazerLogin()
    {
        if ($this->idEmpresa == 153) {
            return;
        }
        $this->Usuario = (new LoginMarktClubModel($this->login, $this->senha, $this->idEmpresa))->Usuario;
    }

    private function criarToken()
    {
        $payload = (new PayloadModel($this->Usuario))->payload;
        $App = new AppEntity();
        $App->uuid(env('API_CLUBE_ID'));

        $Token = new TokenAuthorizationEntity();
        $this->token = $Token->criarToken(
            app: $App,
            body: $payload,
            scope: [],
            audience: $App->audience,
            redirectUri: 'clube.markt.club',
            state: $this->state,
            tipo: new Tipo(Tipo::CLUBE)
        );
    }
}
