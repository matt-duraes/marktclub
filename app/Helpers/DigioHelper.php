<?php

namespace App\Helpers;

use Helpers\CurlHelper;

final class DigioHelper extends CurlHelper
{
    private array $usuario;
    private string $token;

    public function __construct(
        private ?string $id
    ) {
        parent::__construct(env('APIIP_LINK', ''));
        $this->token = env('APIIP_TOKEN', '');

        $this->buscarUsuarioViaCurl();
        $this->validarRetornoUsuario();
        $this->montarUsuario();
    }

    /**
     * Pega o nome, e-mail e CPF do usuário
     *
     * @return array Array com dados do usuário
     */
    public function usuario()
    {
        return $this->usuario;
    }

    private function buscarUsuarioViaCurl()
    {
        $usuario = $this
            ->header([
                'Authorization' => $this->token
            ])
            ->get('/' . $this->id)
            ->array();
        $this->usuario = is_array($usuario) ? $usuario : [];
    }

    private function validarRetornoUsuario()
    {
        $usuario = $this->usuario;
        if (!array_key_exists('cpf', $usuario)) {
            mensagemErro(
                'Erro!',
                'Não foi possível achar seu usuário, por favor, tente novamente.'
            );
        }
    }

    private function montarUsuario()
    {
        $usuario = $this->usuario;
        $this->usuario = [
            'nome'          => $usuario['nome'],
            'email_pessoal' => $usuario['email'],
            'documento'     => $usuario['cpf']
        ];
    }
}
