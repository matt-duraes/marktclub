<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;
use Helpers\CryptHelper;
use App\Classes\LoginClube\Tipo;

final class LogarModel
{
    use CryptTrait;

    private array $token;
    private CryptHelper $Crypt;

    public function __construct(
        private string $login,
        private string $senha
    ) {
        $this->validarDado();
        $this->setarCrypt();
        $this->fazerLogin();
        new AuthModel($this->token);
    }

    private function validarDado()
    {
        if (empty($this->login)) {
            mensagemErro('Campo obrigatório!', 'Digite seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro('Campo obrigatório!', 'Digite sua senha para continuar.');
        }
    }

    private function fazerLogin()
    {
        $dado = (new ApiHelper(scope: 'login:clube'))
            ->validar('Erro ao fazer o login, por favor, tente novamente.')
            ->body([
                'login'        => $this->Crypt->encode($this->login),
                'senha'        => $this->Crypt->encode($this->senha),
                'redirect_uri' => strDominio(LINK, www: false),
                'scope'        => '',
                'state'        => uuid(),
                'tipo'         => Tipo::TITULAR
            ])
            ->post('/login/clube')->array()['dado'];

        $this->token = $dado['token'];
    }
}
