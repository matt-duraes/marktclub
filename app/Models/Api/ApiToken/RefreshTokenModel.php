<?php

namespace App\Models\Api\ApiToken;

use ORM\ORM;
use stdClass;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\AdminConstrutor\ClubeModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

final class RefreshTokenModel extends ORM implements TokenInterface
{
    use TokenTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;
    private stdClass $tokenAtual;
    private EquipeEntity|ClienteEntity $Usuario;
    private array $token;
    public array $clube = [];

    public function __construct(
        private ?AppEntity $App = null,
        private ?string $refreshToken = null,
        private ?string $scope = null
    ) {
        parent::__construct();
        if (is_null($App) || empty($refreshToken)) {
            mensagemStatus(403);
        }
        $this->pegarTokenAtual();
        $this->mudaAppSeTokenForClube();
        $this->validaSeTokenDoApp();
        $this->pegarUsuario();

        $this->token = $this->criarImplicitToken(
            App: $this->App,
            Usuario: $this->Usuario,
            scope: $this->pegarScope(),
            audience: $App->audience,
            redirectUri: $App->redirect_uri[0] ?? '',
            state: $this->tokenAtual->state_cliente,
            tipo: new Tipo($this->tokenAtual->tipo)
        );
    }

    public function pegarToken(): array
    {
        try {
            return $this->token;
        } catch (\Throwable $e) {
            $this->tokenVencido($e, 'Erro ao pegar token');
        }
    }

    private function pegarTokenAtual()
    {
        try {
            $token = $this
                ->campo(['id_usuario', 'id_api_app', 'scope_permitido', 'state_cliente', 'tipo'])
                ->where([
                    ['refresh_token', $this->refreshToken],
                    ['grant_type', 'implicit'],
                    ['ip', ip()],
                    ['status', 1]
                ])->primeiro();
            if (empty($token)) {
                $this->tokenVencido(mensagem: 'Não foi encontrado o token atual.');
            }

            $this->tokenAtual = $token;
        } catch (\Throwable $e) {
            $this->tokenVencido($e, mensagem: 'Erro ao pegar token atual.');
        }
    }

    private function mudaAppSeTokenForClube()
    {
        $AppToken = new AppEntity();
        $AppToken->id($this->tokenAtual->id_api_app);
        if ($AppToken->id != env('API_CLUBE_ID')) {
            return;
        }
        $this->App = $AppToken;
        $Construtor = new ConstrutorEntity();
        $Construtor->id($this->App->id_admin_empresa);
        $this->clube = (new ClubeModel($Construtor))->construtor;
    }

    private function validaSeTokenDoApp()
    {
        if ($this->App->get('id') != $this->tokenAtual->id_api_app) {
            mensagemStatus(403, localhost: 'O Token não é do APP que solicitou o refresh.');
        }
    }

    private function pegarUsuario()
    {
        if (in_array($this->App->audience, ['web'])) {
            $Usuario = new EquipeEntity(validarToken: false);
        } elseif ($this->App->audience == 'clube') {
            $Usuario = new ClienteEntity(validarToken: false);
        }
        try {
            $Usuario->uuid($this->tokenAtual->id_usuario);
        } catch (\Throwable $e) {
            $this->tokenVencido($e, mensagem: 'Erro ao pegar usuário.');
        }
        $this->Usuario = $Usuario;
    }

    private function pegarScope()
    {
        if (!empty($this->scope)) {
            return explode(' ', $this->scope);
        }
        return jsonDecode($this->tokenAtual->scope_permitido);
    }

    public function tokenVencido(?\Throwable $e = null, ?string $mensagem = null)
    {
        mensagemErro('Token vencido!', 'O token enviado está vencido.', status: 403, error: $e, localhost: $mensagem);
    }
}
