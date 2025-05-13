<?php

namespace App\Models\Api\LoginClube;

use App\Classes\ApiToken\Tipo as TokenTipo;
use App\Classes\LoginClube\Tipo;
use App\Models\Api\ApiApp\Trait\AppParaTokenTrait;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\ConstrutorClube\ClubeModel;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\LoginClube\ClubePoupy\UsuarioTrait as UsuarioClubePoupyTrait;
use App\Models\Api\LoginClube\EmporioNaval\UsuarioTrait as UsuarioEmporioNavalTrait;
use App\Models\Api\LoginClube\LeveBeneficios\UsuarioTrait as UsuarioLeveTrait;
use App\Models\Api\LoginClube\UpClube\UsuarioTrait as UsuarioUpClubeTrait;
use App\Models\Api\LoginClube\VivaDiversao\UsuarioTrait as UsuarioVivaTrait;
use App\Models\Api\LoginClube\Youhuul\LoginModel as LoginMarktClubModel;
use App\Models\Api\UsuarioCliente\UsuarioLogadoModel;
use Erro\Excecao;
use Modules\Botao;
use stdClass;
use Throwable;

final class LoginClubeModel
{
    use AppParaTokenTrait;
    use UsuarioEmporioNavalTrait;
    use UsuarioClubePoupyTrait;
    use UsuarioUpClubeTrait;
    use UsuarioLeveTrait;
    use UsuarioVivaTrait;

    public array $token;
    public array $construtor;
    private stdClass $Usuario;
    private int $idEmpresa;
    private array $listaUriHomologacao;

    /**
     * Faz o login normal do usuário com usuario e senha
     *
     * @param string|null $login
     * @param string|null $senha
     * @param string|null $redirectUri
     * @param string|null $state
     * @param string|null $hash
     * @param Tipo        $tipo
     * @param Botao       $cadastro
     * @param Botao       $termo
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?string $login = null,
        private readonly ?string $senha = null,
        private ?string $redirectUri = null,
        private readonly ?string $state = null,
        private readonly ?string $hash = null,
        private readonly Tipo $tipo = new Tipo(),
        private readonly Botao $cadastro = new Botao(),
        private readonly Botao $termo = new Botao()
    ) {
        $this->listaUriHomologacao = env('API_REDIRECT_URI_HOMOLOGACAO', []);
        $this->pegarConstrutor();
        $this->fazerLogin();
        $this->criarToken();
        new UsuarioLogadoModel($this->Usuario->id);
    }

    private function pegarConstrutor(): void
    {
        $redirectUri = explode('/', preg_replace('/^https?\:\/\/(www.)?/', '', $this->redirectUri))[0];
        $this->redirectUri = $redirectUri;
        if (array_key_exists($redirectUri, $this->listaUriHomologacao)) {
            $redirectUri = $this->listaUriHomologacao[$redirectUri];
        }

        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->buscar([
                ['link_clube', $redirectUri], ['status', 1]
            ]);
        } catch (Throwable $e) {
            mensagemStatus(404, localhost: 'Erro ao buscar empresa. ' . $e->getMessage());
        }
        $this->idEmpresa = $Construtor->id_admin_empresa;
        $this->construtor = (new ClubeModel($Construtor))->construtor;
    }

    /**
     * @throws Excecao
     */
    private function fazerLogin(): void
    {
        if ($this->idEmpresa == 153) { // FENAE
            return;
        }

        $isTitular = $this->tipo->indice() === Tipo::TITULAR;
        if ($isTitular && $this->idEmpresa == 2100) {
            $this->Usuario = $this->pegarUsuarioEmporioNaval();
        } elseif ($isTitular && $this->idEmpresa == 2114) {
            $this->Usuario = $this->pegarUsuarioClubePoupy();
        } elseif ($isTitular && $this->idEmpresa == 4639) {
            $this->Usuario = $this->pegarUsuarioLeveBeneficios();
        } elseif ($isTitular && $this->idEmpresa == 4648) {
            $this->Usuario = $this->pegarUsuarioUpClube();
        } elseif ($isTitular && $this->idEmpresa == 4722) {
            $this->Usuario = $this->pegarUsuarioVivaDiversao();
        } else {
            $this->Usuario = $this->pegarUsuarioYouhuul();
        }
    }

    private function pegarUsuarioYouhuul(): stdClass
    {
        return (new LoginMarktClubModel(
            login: $this->login, senha: $this->senha, hash: $this->hash, empresa: $this->idEmpresa
        ))->Usuario;
    }

    private function criarToken(): void
    {
        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID')]);
        $payload = (new PayloadModel($this->Usuario, $App->audience))->payload;

        $Token = new TokenAuthorizationEntity();
        $this->token = $Token->criarToken(
            app: $App,
            body: $payload,
            scope: [],
            audience: $App->audience,
            redirectUri: 'clube.youhuul.com.br',
            state: $this->state,
            empresa: $this->idEmpresa,
            tipo: new TokenTipo(TokenTipo::CLUBE)
        );
    }
}
