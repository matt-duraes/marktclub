<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use Modules\Botao;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\ComercialSubempresa\HelperModel;

trait EntityInsertTrait
{
    protected function regraInsert()
    {
        $this->cod = uuid();
        $this->mensagem = new Botao('sim');

        $this->validarCamposObrigatorioNoInsert();
        $this->setarSubempresa();

        if (!$this->request->existe('status') || empty($this->request->status)) {
            $this->status = new Status(Status::INATIVO);
        }
    }

    private function setarSubempresa()
    {
        if (empty($this->subempresa)) {
            return;
        }
        $SubempresaHelper = new HelperModel();
        $idSubempresa = $SubempresaHelper->pegarIdPeloUuid($this->subempresa, $this->idEmpresa);
        if (empty($idSubempresa)) {
            mensagemErro('Campo inválido!', 'Não foi encontrado nenhuma subempresa pelo código enviado', status: 404);
        }
        $this->id_admin_subempresa = $idSubempresa;
    }

    private function validarCamposObrigatorioNoInsert()
    {
        $request = $this->request;
        $campoObrigatorio = $this->campoObrigatorio;
        $emailPessoal = $request->existe('email_pessoal') ? $this->email_pessoal->email() : '';
        $emailTrabalho = $request->existe('email_trabalho') ? $this->email_trabalho->email() : '';

        if (
            in_array('nome', $campoObrigatorio) &&
            (!$request->existe('nome') || $this->nome->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (
            in_array('cpf', $campoObrigatorio) &&
            (!$request->existe('cpf') || $this->cpf->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (
            in_array('email', $campoObrigatorio) && empty($emailPessoal) && empty($emailTrabalho)
        ) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail para salvar.');
        } elseif (
            in_array('status', $campoObrigatorio) &&
            (!$request->existe('status') || $this->status->vazio())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo status é obrigatório.');
        } elseif (
            in_array('matricula', $campoObrigatorio) &&
            (!$request->existe('matricula') || empty($this->matricula))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo matrícula é obrigatório.');
        } elseif (
            in_array('siape', $campoObrigatorio) &&
            (!$request->existe('siape') || empty($this->siape))
        ) {
            mensagemErro('Campo obrigatório!', 'O campo siape é obrigatório.');
        }
    }
}
