<?php

namespace App\Models\Api\ApiToken;

use stdClass;
use App\Classes\UsuarioCliente\TipoUsuario;

final class PayloadModel
{
    public array $payload;

    public function __construct(
        private stdClass $Usuario,
        string $audience,
        ?string $chavePublica = null
    ) {
        if ($audience == 'clube') {
            $this->montarCliente($Usuario, $chavePublica);
        } elseif ($audience == 'web') {
            $this->montarEquipe($Usuario, $chavePublica);
        }
    }

    private function montarCliente(stdClass $Usuario, ?string $chavePublica)
    {
        $email = !empty($Usuario->email_pessoal) ? $Usuario->email_pessoal : $Usuario->email_trabalho;
        $this->payload = criptografarDado(
            dado: [
                'sub'             => $Usuario->uuid,
                'name'            => $Usuario->nome,
                'picture'         => $Usuario->imagem,
                'document'        => $Usuario->cpf,
                'email'           => $email,
                'email_verified'  => false,
                'type'            => (new TipoUsuario($Usuario->tipo))->indice(),
                'group'           => $Usuario->grupo,
                'new_user'        => $Usuario->primeiro_acesso == 1,
                'update_password' => $Usuario->mudar_senha == 1,
                'lgpd'            => !empty($Usuario->data_termo),
                'create_at'       => $Usuario->data_criacao,
                'updated_at'      => $Usuario->data_atualizacao,
            ],
            criptografia: ['name', 'picture', 'document', 'email'],
            chave: $chavePublica
        );
    }

    private function montarEquipe(stdClass $Usuario, ?string $chavePublica)
    {
        $imagem = imagemUsuario(
            $Usuario->imagem_tipo,
            $Usuario->imagem_arquivo,
            $Usuario->imagem_facebook,
            $Usuario->imagem_google
        );
        $email = !empty($Usuario->email_pessoal) ? $Usuario->email_pessoal : $Usuario->email_trabalho;
        $this->payload = criptografarDado(
            dado: [
                'sub'            => $Usuario->uuid,
                'name'           => $Usuario->nome_real,
                'picture'        => $imagem,
                'email'          => $email,
                'email_verified' => 'nao',
                'create_at'      => $Usuario->data_criacao,
                'updated_at'     => $Usuario->data_atualizacao,
            ],
            criptografia: ['name', 'picture', 'email'],
            chave: $chavePublica
        );
    }
}
