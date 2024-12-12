<?php

namespace App\Controllers\Api\Usuario\Trait;

use App\Classes\ApiApp\Audience;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Controllers\Api\Usuario\EquipeController;
use App\Controllers\Api\Usuario\ClienteController;

trait PerfilInitTrait
{
    private bool $eClube = false;

    private function validarToken()
    {
        if (
            !defined('TOKEN') ||
            !is_array(TOKEN) ||
            !array_key_exists('app', TOKEN) ||
            !object_key_exists('audience', TOKEN['app']) ||
            !in_array(TOKEN['app']->audience, [Audience::CLUBE, Audience::PAINEL]) ||
            !array_key_exists('usuario', TOKEN) ||
            !object_key_exists('id', TOKEN['usuario']) ||
            empty(TOKEN['usuario']->id) ||
            !object_key_exists('uuid', TOKEN['usuario']) ||
            empty(TOKEN['usuario']->uuid)
        ) {
            mensagemStatus(status: 401, localhost: 'Não foi possível validar a audiencia no controller.');
        }
        $this->eClube = TOKEN['app']->audience == Audience::CLUBE;
    }

    private function setarController()
    {
        $this->Controller = $this->eClube ? new ClienteController() : new EquipeController();
        $this->Controller->perfil = true;
        $retorno = $this->eClube ? [
            'nome', 'siape', 'cpf', 'rg', 'email_trabalho', 'email_pessoal', 'email_funcional',
            'telefone_trabalho', 'telefone_pessoal', 'estado_civil', 'genero', 'imagem',
            'data_nascimento', 'matricula', 'federacao', 'endereco_cep', 'endereco_logradouro', 'endereco_numero',
            'endereco_complemento', 'endereco_bairro', 'endereco_cidade', 'endereco_estado',
            'primeiro_acesso', 'possui_senha', 'mudar_senha', 'situacao', 'mensagem',
            'grupo', 'status', 'data_criacao'
        ] : [
            'Empresa' => ['id', 'nome_fantasia'],
            'subempresa', 'perfil', 'nome', 'cpf', 'imagem', 'email_trabalho', 'email_pessoal', 'tipo',
            'telefone_pessoal', 'genero', 'data_nascimento', 'primeiro_acesso', 'mudar_senha',
            'marktclub', 'gerente', 'admin', 'telefone_trabalho', 'status', 'permissao'
        ];
        $this->Controller->dadoRetorno = $retorno;
    }

    private function setarEntity()
    {
        $this->Entity = $this->eClube ? new ClienteEntity(perfil: true) : new EquipeEntity(perfil: true);
    }

    private function setarIdUsuario()
    {
        $this->idUsuario = TOKEN['usuario']->id;
        $this->uuidUsuario = TOKEN['usuario']->uuid;
    }
}
