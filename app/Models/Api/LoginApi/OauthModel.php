<?php

namespace App\Models\Api\LoginApi;

use ORM\Entity;
use App\Models\Api\ApiToken\PayloadModel;
use App\Classes\ApiToken\Tipo as TokenTipo;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use App\Models\Api\ApiApp\Trait\AppParaTokenTrait;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

final class OauthModel extends Entity
{
    use UsuarioTrait;
    use AppParaTokenTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $dadoUsuario;
    private int $idEmpresa;
    private int $idRealUsuario;
    private string $idUsuario = '';
    private int $statusUsuario = 0;
    public array $retorno = [];
    private string $hash = '';
    private string $idToken;

    public function __construct(
        private string $empresa
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        }

        $this->pegarIdToken();
        $this->descriptografarIdToken();

        parent::__construct();
        $this->idEmpresa = 153;
        $this->verificarSeUsuarioJaExiste();

        if (!empty($this->idUsuario)) {
            $this->atualizarUsuarioJaExistente();
            $this->criarToken();
            return;
        }
        $this->salvarNovoUsuario();
        $this->criarToken();
    }

    private function pegarIdToken()
    {
        $header = getallheaders();
        $idToken = $header['Token'] ?? $header['token'] ?? $header['TOKEN'] ?? '';
        if (empty($idToken)) {
            $this->mensagemErroPadrao();
        }
        $this->idToken = $idToken;
    }

    private function descriptografarIdToken()
    {
        $Usuario = jsonDecode(base64_decode(str_replace(['_', '='], ['/', ''], explode('.', $this->idToken)[1])), true, true);
        if (!is_array($Usuario) || !array_key_exists('cpf', $Usuario)) {
            $this->mensagemErroPadrao();
        }
        $this->dadoUsuario = [
            'nome'            => $Usuario['name'],
            'documento'       => $Usuario['cpf'],
            'email_pessoal'   => $Usuario['email'],
            'data_nascimento' => $Usuario['birthdate'] ?? '',
        ];
    }

    private function mensagemErroPadrao()
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer seu login, por favor, tente novamente.');
    }

    private function criarToken(): void
    {
        $Usuario = $this->campo([
            'id', 'uuid', 'salt', 'cpf', 'nome', 'imagem', 'email_pessoal', 'email_trabalho', 'tipo',
            'grupo', 'primeiro_acesso', 'mudar_senha', 'data_termo', 'data_criacao', 'data_atualizacao',
            'federacao', 'imagem_arquivo', 'status'
        ])->where(['id', $this->idRealUsuario])->primeiro();

        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID')]);
        $payload = (new PayloadModel($Usuario, $App->audience))->payload;

        $Token = new TokenAuthorizationEntity();
        $this->retorno = $Token->criarToken(
            app: $App,
            body: $payload,
            scope: [],
            audience: $App->audience,
            redirectUri: 'clube.youhuul.com.br',
            state: uuid(),
            empresa: $this->idEmpresa,
            tipo: new TokenTipo(TokenTipo::CLUBE)
        );
    }
}
