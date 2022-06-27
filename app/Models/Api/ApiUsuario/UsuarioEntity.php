<?php

namespace App\Models\Api\ApiUsuario;

use ORM\Entity;
use Modules\Nome;
use Modules\Senha;
use App\Models\Api\ApiApp\AppModel;

final class UsuarioEntity extends Entity
{
    protected string $_tabela = TABELA_AUTH_USUARIO;
    protected array $_buscar = [
        'nome' => 'nome_usuario',
        'senha' => 'salt',
        'id_api_app', 'login_usuario'
    ];
    protected array $_update = ['salt' => '->senha'];

    public Nome $nome;
    public Senha $senha;
    protected array $id_api_app;

    public function pegarApp()
    {
        return (new AppModel)->listarAppPeloId($this->id_api_app);
    }

    protected function regraUpdate()
    {
        if ($this->senha->vazio()) {
            mensagemErro('Senha obrigatória!', 'Você precisa enviar uma senha para salvar.');
        } else if (!$this->senha->valido()) {
            mensagemErro('Senha inválida!', $this->senha->mensagem());
        } else if ($this->senha->mesmaSenha()) {
            mensagemErro('Senha inválida!', 'Você não pode salvar a mesma senha novamente.');
        }
    }
}
