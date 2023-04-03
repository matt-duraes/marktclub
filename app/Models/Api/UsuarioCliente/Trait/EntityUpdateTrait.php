<?php

namespace App\Models\Api\UsuarioCliente\Trait;

trait EntityUpdateTrait
{
    protected function regraUpdate()
    {
        $cpfAtual = $this->prop('documento');
        if (!empty($cpfAtual) && validarCpf($cpfAtual) && $cpfAtual != $this->cpf->numero()) {
            mensagemErro('Erro!', 'Você não pode mudar o CPF desse usuário.');
        }
        $this->validarCamposObrigatorioNoUpdate();
    }
    private function validarCamposObrigatorioNoUpdate()
    {
        $campoObrigatorio = $this->campoObrigatorio;
        $request = $this->request;
        $emailExiste = $request->existe('email_pessoal') || $request->existe('email_trabalho');

        if (
            in_array('nome', $campoObrigatorio) && $request->existe('nome') && $this->nome->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (
            in_array('cpf', $campoObrigatorio) && $request->existe('cpf') && $this->cpf->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (
            in_array('email', $campoObrigatorio) &&
            $emailExiste &&
            $this->email_pessoal->vazio() &&
            $this->email_trabalho->vazio()
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } elseif (
            in_array('status', $campoObrigatorio) && $request->existe('status') && !$this->status->valido()
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } elseif (
            in_array('matricula', $campoObrigatorio) && $request->existe('matricula') && empty($this->matricula)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } elseif (
            in_array('siape', $campoObrigatorio) && $request->existe('siape') && empty($this->siape)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }
}
