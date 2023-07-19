<?php

namespace App\Models\Api\ApiToken;

use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PayloadModel
{
    public array $payload;

    public function __construct(
        private ClienteEntity|EquipeEntity $Usuario
    ) {
        if ($Usuario instanceof ClienteEntity) {
            $this->montarCliente($Usuario);
        } elseif ($Usuario instanceof EquipeEntity) {
            $this->montarEquipe($Usuario);
        }
    }

    private function montarCliente(ClienteEntity $Usuario)
    {
        $this->payload = criptografarDado(
            dado: [
                'sub'             => $Usuario->id,
                'name'            => $Usuario->nome->nome(),
                'picture'         => $Usuario->imagem,
                'document'        => $Usuario->cpf->cpf(),
                'email'           => $Usuario->email->email(),
                'email_verified'  => false,
                'type'            => $Usuario->tipo->indice(),
                'group'           => $Usuario->grupo,
                'new_user'        => $Usuario->primeiro_acesso->bool(),
                'update_password' => $Usuario->mudar_senha->bool(),
                'lgpd'            => $Usuario->termo->bool(),
                'create_at'       => $Usuario->data_criacao->date(),
                'updated_at'      => $Usuario->data_atualizacao->date(),
            ],
            criptografia: ['name', 'picture', 'document', 'email']
        );
    }

    private function montarEquipe(EquipeEntity $Usuario)
    {
        $this->payload = criptografarDado(
            dado: [
                'sub'            => $Usuario->id,
                'name'           => $Usuario->nome->nome(),
                'picture'        => $Usuario->imagem,
                'email'          => $Usuario->email->email(),
                'email_verified' => 'nao',
                'create_at'      => $Usuario->data_criacao->date(),
                'updated_at'     => $Usuario->data_atualizacao->date(),
            ],
            criptografia: ['name', 'picture', 'email']
        );
    }
}
