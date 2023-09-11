<?php

namespace App\Models\Api\UsuarioCliente\Senha;

use ORM\ORM;
use stdClass;
use Modules\Senha;
use App\Classes\UsuarioCliente\Hash;

final class AlterarSenhaModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $erroPadrao = 'Ocorreu um erro ao atualizar sua senha, por favor, tente novamente.';
    private stdClass $usuario;

    public function __construct(
        private Senha $senha,
        private string $id,
        private string $hash,
    ) {
        parent::__construct();
        $this->validarDado();
        $this->buscarUsuario();
        $this->validarHash();
        $this->salvarSenha();
    }

    private function validarDado()
    {
        if (empty($this->id) || empty($this->hash)) {
            mensagemErro('Erro!', $this->erroPadrao);
        } elseif ($this->senha->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        } elseif (!$this->senha->valido()) {
            mensagemErro('Campo invalido!', 'O campo senha não é uma senha válida.');
        }
    }

    private function buscarUsuario()
    {
        $usuario = $this->campo(['id', 'hash', 'hash_data', 'hash_tipo'])->where(['uuid', $this->id])->primeiro();
        if (!$usuario) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
        $this->usuario = $usuario;
    }

    private function validarhash()
    {
        if ($this->usuario->hash != $this->hash || $this->usuario->hash_tipo != Hash::RECUPERAR_SENHA) {
            mensagemErro(
                'Erro!',
                $this->erroPadrao
            );
        } elseif ($this->usuario->hash_data < dataRemover(agora(), 10, 'minutos', 'Y-m-d H:i:s')) {
            mensagemErro(
                'Procedimento vencido!',
                'O procedimento venceu, recomeçe a processo para recuperar sua senha.'
            );
        }
    }

    private function salvarSenha()
    {
        $usuario = $this
            ->dado([
                'salt'         => $this->senha->senha(),
                'codigo_valor' => '',
                'codigo_data'  => '',
                'hash'         => '',
                'hash_data'    => '',
                'hash_tipo'    => '',
            ])
            ->where(['id', $this->usuario->id])
            ->update();
        if (!$usuario) {
            mensagemErro('Erro!', $this->erroPadrao);
        }
    }
}
