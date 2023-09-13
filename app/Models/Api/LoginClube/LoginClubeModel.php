<?php

namespace App\Models\Api\LoginClube;

use stdClass;
use Http\Request;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\ConstrutorClube\ClubeModel;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\UsuarioCliente\UsuarioLogadoModel;

final class LoginClubeModel
{
    use PegarAppTrait;

    private stdClass $Usuario;
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
        private ?string $login = null,
        private ?string $senha = null,
        private ?string $redirectUri = null,
        private ?string $state = null,
        private ?string $hash = null,
    ) {
        $this->listaUriHomologacao = env('API_REDIRECT_URI_HOMOLOGACAO', []);
        $this->pegarConstrutor();
        $this->fazerLogin();
        $this->criarToken();
        new UsuarioLogadoModel($this->Usuario->id);
    }

    private function pegarConstrutor()
    {
        $redirectUri = explode('/', preg_replace('/^https?\:\/\//', '', $this->redirectUri))[0];
        $this->redirectUri = $redirectUri;
        if (!eProducao() && array_key_exists($redirectUri, $this->listaUriHomologacao)) {
            $redirectUri = $this->listaUriHomologacao[$redirectUri];
        }

        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->buscar([
                ['link_clube', $redirectUri],
                ['status', 1]
            ]);
        } catch (\Throwable $e) {
            mensagemStatus(404, localhost: 'Erro ao buscar empresa. ' . $e->getMessage());
        }
        $this->idEmpresa = $Construtor->id_admin_empresa;
        $this->construtor = (new ClubeModel($Construtor))->construtor;
    }

    private function fazerLogin()
    {
        if ($this->idEmpresa == 153) {
            return;
        }
        $this->Usuario = (new LoginMarktClubModel(
            login: $this->login,
            senha: $this->senha,
            hash: $this->hash,
            empresa: $this->idEmpresa
        ))->Usuario;
    }

    private function criarToken()
    {
        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID')]);
        $payload = (new PayloadModel($this->Usuario, $App->audience))->payload;

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
