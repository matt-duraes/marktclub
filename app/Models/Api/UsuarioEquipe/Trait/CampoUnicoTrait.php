<?php

namespace App\Models\Api\UsuarioEquipe\Trait;

trait CampoUnicoTrait
{
    /**
     * Valida se CPF Já existe
     */
    protected function cpfExiste()
    {
        if (!$this->propriedadeExiste('cpf') || empty($this->cpf->numero())) {
            return;
        }

        $cpf = $this->cpf->numero();
        $where = [
            ['documento_cpf', $cpf],
            ['id_admin_empresa', $this->idEmpresa]
        ];

        if ($this->entityExiste) {
            $where[] = ['id', '!=', $this->prop('id')];
        }

        if ($this->existe($where)) {
            mensagemErro('CPF já existe!', 'O CPF informado já está em uso por outro usuário.');
        }
    }

    /**
     * Valida se e-mail de trabaho Já existe
     */
    protected function emailTrabalhoExiste()
    {
        if (!$this->propriedadeExiste('email_trabalho') || empty($this->email_trabalho->email())) {
            return;
        }

        $where = [
            [
                'OR',
                ['email_trabalho', $this->email_trabalho->email()],
                ['email_pessoal', $this->email_trabalho->email()]
            ],
            ['id_admin_empresa', $this->idEmpresa]
        ];

        if ($this->entityExiste) {
            $where[] = ['id', '!=', $this->prop('id')];
        }

        if ($this->existe($where)) {
            mensagemErro('E-mail já existe!', 'O E-mail de trabalho informado já está em uso por outro usuário.');
        }
    }

    /**
     * Valida se e-mail pessoal Já existe
     */
    protected function emailPessoalExiste()
    {
        if (!$this->propriedadeExiste('email_pessoal') || empty($this->email_pessoal->email())) {
            return;
        }
        $where = [
            ['email_pessoal', $this->email_pessoal->email()],
            ['id_admin_empresa', $this->idEmpresa]
        ];

        if ($this->entityExiste) {
            $where[] = ['id', '!=', $this->prop('id')];
        }

        if ($this->existe($where)) {
            mensagemErro('E-mail já existe!', 'O E-mail pessoal informado já está em uso por outro usuário.');
        }
    }
}
