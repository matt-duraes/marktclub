<?php

namespace PainelApp\login\Models;

use stdClass;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use PainelApp\login\Models\Trait\ChaveTrait;

final class BuscarUsuarioModel
{
    use ChaveTrait;

    private string $idUsuario;
    private stdClass $usuario;

    public function __construct()
    {
        $this->idUsuario = sessao('USUARIO.id');
        $this->setarChaves();
        $this->buscarDadoUsuario();
        $this->remontarSessaoUsuario();
    }

    private function buscarDadoUsuario()
    {
        $this->usuario = (new ApiHelper(token: true))
            ->validar('Ocorreu um erro ao fazer seu login, por favor, tente novamente.')
            ->get('/usuario-equipe/' . $this->idUsuario)
            ->object()
            ->dado;
    }

    private function remontarSessaoUsuario()
    {
        $Crypt = new CryptHelper(chavePrivada: $this->chavePrivada);
        $body = $this->usuario;

        $cpf = $Crypt->decode($body->cpf);

        $emailPessoal = $Crypt->decode($body->email_pessoal);
        $emailTrabalho = $Crypt->decode($body->email_trabalho);
        $email = !empty($emailTrabalho) ? $emailTrabalho : $emailPessoal;

        sessao('USUARIO', [
            'id' => $body->id,
            'nome' => $Crypt->decode($body->nome),
            'email' => $email,
            'imagem' => $Crypt->decode($body->imagem),
            'cpf' => $cpf,
            'google' => $Crypt->decode($body->google ?? ''),
            'facebook' => $Crypt->decode($body->facebook ?? ''),
            'permissao' => $body->permissao,
            'empresa' => $body->empresa->slug ?? '',
            'gerente' => $body->gerente ?? '',
            'dev' => in_array($cpf, jsonDecode(env('DEV_DOCUMENTO', []), true, true))
        ]);
    }
}
