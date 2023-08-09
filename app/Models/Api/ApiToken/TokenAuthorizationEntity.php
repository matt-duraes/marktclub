<?php

namespace App\Models\Api\ApiToken;

use stdClass;
use ORM\Entity;
use Modules\DataHora;
use Helpers\JwtHelper;
use App\Classes\Geral\Status;
use App\Classes\ApiToken\Tipo;

final class TokenAuthorizationEntity extends Entity
{
    use Trait\ScopeTrait;
    use Trait\RedirectUriTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;

    // protected array $ormBuscar = [];
    protected array $ormInsert = [
        'id_usuario', 'id_api_app', 'redirect_uri', 'scope_permitido', 'state_cliente', 'authorization_code',
        'access_token', 'grant_type', 'ip', 'sistema_operacional', 'navegador', 'data_ativacao', 'data_vencimento',
        'status', 'refresh_token', 'hash', 'tipo'
    ];
    protected string $id_usuario;
    protected int $id_api_app;
    protected string $redirect_uri;
    protected array $scope_permitido;
    protected string $state_cliente;
    protected string $authorization_code;
    protected string $access_token;
    protected string $grant_type;
    protected string $ip;
    protected string $sistema_operacional;
    protected string $navegador;
    protected DataHora $data_ativacao;
    protected DataHora $data_vencimento;
    protected string $hash;
    protected Status $status;
    protected string $refresh_token;
    protected Tipo $tipo;

    public function criarToken(
        stdClass $app,
        array $body,
        array $scope,
        string $audience,
        string $redirectUri,
        string $state,
        ?Tipo $tipo = null
    ) {
        if ($app->id != env('API_CLUBE_ID') && !in_array($redirectUri, (array)$app->redirect_uri)) {
            mensagemErro('Erro!', 'Redirect Uri não está autorizado a criar token.', 403);
        } elseif (empty($audience)) {
            mensagemErro('Campo incorreto!', 'Não foi enviado o audience do app.');
        } elseif (empty($state)) {
            mensagemErro('Campo incorreto!', 'Não foi enviado o state do usuário.');
        }
        $tempoVida = $app->tempo_vida ?? 3600;
        $scope = $this->pegarScope($scope, $app->scope_permitido);
        $jwt = $this->criarJwt($body, $app, $audience, $scope, $tempoVida);

        $accessToken = uuid();
        $refreshToken = uuid();
        $this->salvarToken($accessToken, $refreshToken, $body, $app, $scope, $redirectUri, $state, $tipo);

        $token = [
            'access_token'  => $accessToken,
            'id_token'      => $jwt,
            'scope'         => implode(' ', $scope),
            'expires_in'    => $tempoVida,
            'token_type'    => 'Bearer',
            'refresh_token' => $refreshToken
        ];

        return $token;
    }

    private function salvarToken($accessToken, $refreshToken, $body, $app, $scope, $redirectUri, $state, $tipo)
    {
        $idApp = $app->id;
        $this->id_usuario = $body['sub'];
        $this->id_api_app = $idApp;
        $this->redirect_uri = $redirectUri;
        $this->scope_permitido = $scope;
        $this->state_cliente = $state;
        $this->authorization_code = uuid();
        $this->access_token = $accessToken;
        $this->grant_type = 'implicit';
        $this->ip = ip();
        $this->sistema_operacional = '';
        $this->navegador = '';
        $this->data_ativacao = new DataHora(agora());
        $this->data_vencimento = new DataHora(date('Y-m-d H:i:s', time() + 86400));
        $this->hash = uuid();
        $this->status = new Status(Status::ATIVO);
        $this->tipo = $tipo;
        $this->refresh_token = $refreshToken;
        try {
            $this->salvar();
            $this->where([
                ['uuid', '!=', $this->id],
                ['id_api_app', $idApp],
                ['id_usuario', $body['sub']]
            ])->delete();
        } catch (\Throwable $e) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao salvar seu token.',
                localhost: 'Ocorreu um erro ao salvar seu token no TokenAuthorizationEntity.',
                error: $e
            );
        }
    }

    private function criarJwt($body, $app, $audience, $scope, $tempoVida)
    {
        $criado = time();
        $payload = [
            'iss'   => LINK,
            'aud'   => $audience,
            'iat'   => $criado,
            'exp'   => time() + $tempoVida,
            'azp'   => $app->client_id,
            'scope' => implode(' ', $scope),
            'gty'   => 'implicit',
        ];
        $payload = array_merge($payload, $body);

        try {
            return (new JwtHelper())->encode($payload);
        } catch (\Throwable) {
            mensagemStatus(500, localhost: 'TokenAuthorizationEntity - Não foi possivel criar o JWT.');
        }
    }
}
