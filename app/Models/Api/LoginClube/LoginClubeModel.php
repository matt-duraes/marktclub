<?php

namespace App\Models\Api\LoginClube;

use Http\Request;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\LoginClube\LoginMarktClubModel;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

final class LoginClubeModel
{
    private ClienteEntity $Usuario;
    private AppEntity $App;
    public array $token;
    public array $construtor;

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
        $this->pegarApp();
        $this->fazerLogin();
        $this->pegarConstrutor();
        $this->criarToken();
    }

    private function pegarApp()
    {
        $this->App = new AppEntity();
        $this->App->buscar(
            where: [
                ['redirect_uri', 'LIKE', '%"' . strDominio($this->redirectUri) . '"%'],
                ['status', 1]
            ],
            mensagem: 'Não foi possível validar a requisição para fazer login do usuário.'
        );
    }
    private function fazerLogin()
    {
        if ($this->App->id_admin_empresa == 153) {
            return;
        }
        $this->Usuario = (new LoginMarktClubModel($this->login, $this->senha, $this->App->id_admin_empresa))->Usuario;
    }
    private function criarToken()
    {
        $Usuario = $this->Usuario;
        $payload = criptografarDado(
            dado: [
                'sub' => $Usuario->id,
                'name' => $Usuario->nome->nome(),
                'picture' => $Usuario->imagem,
                'document' => $Usuario->cpf->cpf(),
                'email' => $Usuario->email->email(),
                'email_verified' => false,
                'type' => $Usuario->tipo->indice(),
                'group' => $Usuario->grupo,
                'new_user' => $Usuario->primeiro_acesso->bool(),
                'update_password' => $Usuario->mudar_senha->bool(),
                'lgpd' => $Usuario->termo->bool(),
                'create_at' => $Usuario->data_criacao->date(),
                'updated_at' => $Usuario->data_atualizacao->date(),
            ],
            criptografia: ['name', 'picture', 'document', 'email']
        );

        $App = $this->App;
        $Token = new TokenAuthorizationEntity();
        $this->token = $Token->criarToken(
            $App,
            $payload,
            [],
            $App->audience,
            $this->redirectUri,
            $this->state,
            new Tipo(Tipo::CLUBE)
        );
    }
    private function pegarConstrutor()
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['empresa', $this->App->id_admin_empresa],
            ['status', 1]
        ]);
        $this->construtor = [
            'id' => $Construtor->id,
            'titulo' => $Construtor->titulo,
            'cor' => $Construtor->cor,
            'menu' => [
                'convenio' => $Construtor->menu_convenio->valor(),
                'convenio_mapa' => $Construtor->menu_convenio_mapa->valor(),
                'cinema' => $Construtor->menu_cinema->valor(),
                'turismo' => $Construtor->menu_turismo->valor(),
                'promocao' => $Construtor->menu_promocao->valor(),
                'sicoob_credito' => $Construtor->menu_sicoob_credito->valor(),
                'medicamento' => $Construtor->menu_medicamento->valor(),
                'automovel' => $Construtor->menu_automovel->valor(),
                'saude_vitoria' => $Construtor->menu_saude_vitoria->valor(),
                'saude_amil' => $Construtor->menu_saude_amil->valor(),
                'saude_seguros' => $Construtor->menu_saude_seguros->valor(),
                'cashback' => $Construtor->menu_cashback->valor(),
                'indicacao' => $Construtor->menu_indicacao->valor(),
                'cupom' => $Construtor->menu_cupom->valor(),
                'odontologia' => $Construtor->menu_odontologia->valor(),
                'premium' => $Construtor->menu_premium->valor(),
                'dependente' => $Construtor->menu_dependente->valor(),
                'carteiria' => $Construtor->menu_carteiria->valor(),
                'salavip' => $Construtor->menu_salavip->valor(),
            ]
        ];
    }
}
