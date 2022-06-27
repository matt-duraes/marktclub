<?php

namespace Painel\ApiApp\Models;

use stdClass;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class ApiAppEntity extends AppGeralEntity
{
    protected string $_tabela = TABELA_API_APP;

    protected array $_salvar = [
        'id_admin_empresa', 'imagem_app', 'nome', 'audience', 'client_credentials',
        'authorization_code', 'refresh_token', 'redirect_uri', 'tempo_vida', 'status'
    ];
    protected array $_insert = ['client_secret', 'client_id'];
    protected array $_buscar = [
        'id_admin_empresa', 'imagem_app', 'nome', 'audience', 'client_credentials',
        'authorization_code', 'refresh_token', 'redirect_uri', 'tempo_vida', 'status'
    ];

    protected function regraSalvar()
    {
        if ($this->tipo == 1) {
            $this->client_credentials = 2;
            $this->authorization_code = 1;
        } else if ($this->tipo == 2) {
            $this->client_credentials = 1;
            $this->authorization_code = 2;
        }
    }
    protected function regraInsert()
    {
        $this->client_id = $this->criarUmClientIdUnico();
        $this->client_secret = $this->criarUmClientSecretUnico();
    }
    private function criarUmClientIdUnico()
    {
        $codigo = strCodigo(22);
        if ($this->existe(['client_id', $codigo])) {
            return $this->criarUmClientIdUnico();
        }
        return $codigo;
    }
    private function criarUmClientSecretUnico()
    {
        $codigo = strCodigo(60);
        if ($this->existe(['client_secret', $codigo])) {
            return $this->criarUmClientSecretUnico();
        }
        return $codigo;
    }

    /*
    |--------------------------------------------------------------------------
    | DADOS PARA EDITAR
    |--------------------------------------------------------------------------
    */
    public function dadoEditar(): stdClass
    {
        return (object) [
            'id' => $this->id,
            'imagem_app' => $this->imagem_app,
            'nome' => $this->nome,
            'audience' => $this->audience,
            'tipo' => $this->authorization_code == 1 ? 1 : 2,
            'redirect_uri' => $this->redirect_uri,
            'refresh_token' => $this->refresh_token == 1,
            'tempo_vida' => $this->tempo_vida,
            'status' => $this->status == 1 ? 1 : 2,
        ];
    }
}
