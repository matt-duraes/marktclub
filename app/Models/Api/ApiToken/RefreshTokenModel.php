<?php

namespace App\Models\Api\ApiToken;

use ORM\ORM;
use stdClass;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class RefreshTokenModel extends ORM implements TokenInterface
{
    use TokenTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;
    private stdClass $tokenAtual;
    private EquipeEntity $Usuario;
    private array $token;

    public function __construct(
        private ?AppEntity $App = null,
        private ?string $refreshToken = null,
        private ?string $scope = null
    ) {
        parent::__construct();
        if (is_null($App) || empty($refreshToken)) {
            return;
        }

        $this->pegarTokenAtual();
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
                ->campo(['id_usuario', 'scope_permitido', 'state_cliente', 'tipo'])
                ->where([
                    ['id_api_app', $this->App->get('id')],
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

    private function pegarUsuario()
    {
        if (in_array($this->App->audience, ['web'])) {
            $Usuario = new EquipeEntity(validarToken: false);
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
