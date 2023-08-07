<?php

namespace App\Models\Api\ApiToken;

use ORM\ORM;
use stdClass;
use App\Classes\ApiToken\Tipo;
use App\Classes\LoginClube\PegarClienteTrait;
use App\Classes\LoginPainel\PegarEquipeTrait;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\AdminConstrutor\ClubeModel;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

final class RefreshTokenModel extends ORM implements TokenInterface
{
    use TokenTrait;
    use PegarEquipeTrait;
    use PegarClienteTrait;
    use PegarAppTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;
    private stdClass $tokenAtual;
    private stdClass $Usuario;
    private array $token;
    public array $clube = [];
    private stdClass $App;

    public function __construct(
        private ?string $clientId = null,
        private ?string $secretId = null,
        private ?string $refreshToken = null,
        private ?string $scope = null
    ) {
        parent::__construct();
        if (empty($clientId) || empty($secretId) || empty($refreshToken)) {
            mensagemStatus(403);
        }
        $this->pegarTokenAtual();
        $this->pegarAppRealOuClube();
        $this->validaSeTokenDoApp();
        $this->pegarUsuarioParaToken();

        $this->token = $this->criarImplicitToken(
            App: $this->App,
            Usuario: $this->Usuario,
            scope: $this->pegarScope(),
            audience: $this->App->audience,
            redirectUri: $this->App->redirect_uri[0] ?? '',
            state: $this->tokenAtual->state_cliente,
            tipo: new Tipo($this->tokenAtual->tipo),
            chave: $this->App->chave_publica
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
        $token = $this
            ->campo(['id_usuario', 'id_api_app', 'scope_permitido', 'state_cliente', 'tipo'])
            ->where([
                ['refresh_token', $this->refreshToken],
                ['grant_type', 'implicit'],
                ['ip', ip()],
                ['status', 1]
            ])->primeiro();
        if (vazio($token)) {
            $this->tokenVencido(mensagem: 'Não foi encontrado o token atual.');
        }
        $this->tokenAtual = $token;
    }

    private function pegarAppRealOuClube()
    {
        $AppToken = $this->pegarApp(['id', $this->tokenAtual->id_api_app]);
        if ($AppToken->uuid == env('API_CLUBE_ID')) {
            $this->pegarAppDoClube($AppToken);
            return;
        }
        $this->pegarAppNormal();
    }

    private function pegarAppDoClube($App)
    {
        $this->App = $App;
        $Construtor = new ConstrutorEntity();
        $Construtor->id($this->App->id_admin_empresa);
        $this->clube = (new ClubeModel($Construtor))->construtor;
    }

    private function pegarAppNormal()
    {
        $this->App = $this->pegarApp([
            ['client_id', $this->clientId],
            ['secret_id', $this->secretId],
            ['client_credentials', 1],
            ['status', 1]
        ]);
    }

    private function validaSeTokenDoApp()
    {
        if ($this->App->id != $this->tokenAtual->id_api_app) {
            mensagemStatus(403, localhost: 'O Token não é do APP que solicitou o refresh.');
        }
    }

    private function pegarUsuarioParaToken()
    {
        $where = ['uuid', $this->tokenAtual->id_usuario];
        if ($this->App->audience == 'web') {
            $Usuario = $this->pegarEquipe($where);
        } elseif ($this->App->audience == 'clube') {
            $Usuario = $this->pegarCliente($where);
        }
        if (vazio($Usuario)) {
            $this->tokenVencido(null, mensagem: 'Erro ao pegar usuário.');
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
