<?php

namespace App\Models\Api\LoginApi\Trait;

use Modules\Cpf;
use App\Classes\UsuarioCliente\Helper;

trait UsuarioTrait
{
    private function verificarSeUsuarioJaExiste()
    {
        $usuario = $this->campo(['cod', 'status'])->where([
            ['empresa', $this->idEmpresa],
            ['documento', $this->dadoUsuario['documento']],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ])->primeiro();

        if (existeErro($usuario, 'cod')) {
            return;
        }
        $this->idUsuario = $usuario->cod;
        $this->statusUsuario = $usuario->status;
    }

    private function atualizarUsuarioJaExistente()
    {
        $agora = agora();
        $hoje = hoje();

        $dado = [
            'tipo' => 1,
            'data_atualizacao' => $agora,
            'data_dado' => $hoje,
            'hash' => $this->hash,
            'hash_data' => agora(),
            'status' => 1
        ];

        if (
            array_key_exists('email_pessoal', $this->dadoUsuario) ||
            array_key_exists('email_trabalho', $this->dadoUsuario)
        ) {
            $dado['data_email'] = $hoje;
        }
        if ($this->statusUsuario != 1) {
            $dado['primeiro_acesso'] = 1;
        }
        if (array_key_exists('documento', $this->dadoUsuario)) {
            unset($this->dadoUsuario['documento']);
        }

        $salvar = $this->dado(array_merge($this->dadoUsuario, $dado))->where(['cod', $this->idUsuario])->update();
        if (existeErro($salvar, 'id') || empty($salvar['id'])) {
            mensagemErro('Erro ao atualizar!', 'Ocorreu um erro ao atualizar o usuário.', status: 500);
        }
    }
    private function salvarNovoUsuario()
    {
        $agora = agora();
        $hoje = hoje();

        $dado = [
            'cod' => uuid(),
            'tipo' => 1,
            'data_atualizacao' => $agora,
            'data_dado' => $hoje,
            'empresa' => $this->idEmpresa,
            'data_criacao' => $agora,
            'primeiro_acesso' => 1,
            'hash' => $this->hash,
            'hash_data' => agora(),
            'status' => 1
        ];

        if (
            array_key_exists('email_pessoal', $this->dadoUsuario) ||
            array_key_exists('email_trabalho', $this->dadoUsuario)
        ) {
            $dado['data_email'] = $hoje;
        }

        $salvar = $this->dado(array_merge($this->dadoUsuario, $dado))->insert();
        if (existeErro($salvar, 'id') || empty($salvar['id'])) {
            mensagemErro('Erro ao salvar!', 'Ocorreu um erro ao criar o usuário.', status: 500);
        }
        $this->idUsuario = $salvar['id'];
    }
}
