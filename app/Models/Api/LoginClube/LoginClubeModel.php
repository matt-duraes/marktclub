<?php

namespace App\Models\Api\LoginClube;

use App\Classes\ApiToken\Tipo as TokenTipo;
use App\Classes\LoginClube\Tipo;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\ConstrutorClube\ClubeModel;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\LoginApi\NavalModel;
use App\Models\Api\UsuarioCliente\UsuarioLogadoModel;
use Erro\Excecao;
use Modules\Botao;
use stdClass;

final class LoginClubeModel
{
    use PegarAppTrait;

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
        private ?string $login = null,
        private ?string $senha = null,
        private ?string $redirectUri = null,
        private ?string $state = null,
        private ?string $hash = null,
        private Tipo $tipo = new Tipo(null),
        private Botao $cadastro = new Botao(null),
        private Botao $termo = new Botao(null)
    ) {
        $this->listaUriHomologacao = env('API_REDIRECT_URI_HOMOLOGACAO', []);
        $this->pegarConstrutor();
        $this->fazerLogin();
        $this->criarToken();
        new UsuarioLogadoModel($this->Usuario->id);
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarConstrutor(): void
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

    /**
     * @return void
     * @throws Excecao
     */
    private function fazerLogin(): void
    {
        if ($this->idEmpresa == 153) { // FENAE
            return;
        } elseif ($this->idEmpresa == 2114 && $this->tipo->indice() == Tipo::TITULAR) { // CLUBE POUPY
            $this->Usuario = (new LoginClubePoupyModel(
                login: soNumero($this->login),
                senha: $this->senha,
                empresa: $this->idEmpresa,
                cadastro: $this->cadastro,
                termo: new Botao($this->termo)
            ))->Usuario;
            return;
        } elseif ($this->idEmpresa == 2100) {
            $this->Usuario = (new NavalModel(
                soNumero($this->login),
                $this->senha,
                $this->idEmpresa,
                $this->cadastro,
                new Botao($this->termo)
            ))->Usuario;
            return;
        }

        $this->Usuario = (new LoginMarktClubModel(
            login: $this->login,
            senha: $this->senha,
            hash: $this->hash,
            empresa: $this->idEmpresa
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
            redirectUri: 'clube.markt.club',
            state: $this->state,
            empresa: $this->idEmpresa,
            tipo: new TokenTipo(TokenTipo::CLUBE)
        );
    }
}
