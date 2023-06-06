<?php

namespace App\Models\Oauth\Fenae;

use Modules\Cpf;
use Modules\Nome;
use Modules\Email;

final class PegarUsuarioModel
{
    private string $idToken = '';
    /**
     * Nome do usuário
     *
     * @var string
     */
    public Nome $nome;
    public Cpf $cpf;
    public Email $email;

    public function __construct()
    {
        $this->pegarIdToken();
        $this->montarUsuario();
    }

    private function pegarIdToken()
    {
        if (!cookieExiste('MKCLTI')) {
            return;
        }
        $this->idToken = cookie('MKCLTI');
    }
    private function montarUsuario()
    {
        $usuario = jsonDecode(base64_decode(explode('.', $this->idToken)[1] ?? ''), retorno: true);
        $this->nome = new Nome($usuario['name'] ?? '');
        $this->cpf = new Cpf($usuario['cpf'] ?? '');
        $this->email = new Email($usuario['email'] ?? '');
    }
}
