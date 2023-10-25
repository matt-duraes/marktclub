<?php

namespace App\Models\Api\UsuarioCliente\Trait;

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
            ['documento', $cpf],
            ['empresa', $this->idEmpresa]
        ];

        if ($this->ormEntityExiste) {
            $where = [
                ['documento', $cpf],
                ['empresa', $this->id_admin_empresa],
                ['id', '!=', $this->prop('id')]
            ];
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
            ['empresa', $this->idEmpresa]
        ];

        if ($this->ormEntityExiste) {
            $where = [
                [
                    'OR',
                    ['email_trabalho', $this->email_trabalho->email()],
                    ['email_pessoal', $this->email_trabalho->email()]
                ],
                ['empresa', $this->id_admin_empresa],
                ['id', '!=', $this->prop('id')]
            ];
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
            [
                'OR',
                ['email_trabalho', $this->email_pessoal->email()],
                ['email_pessoal', $this->email_pessoal->email()]
            ],
            ['empresa', $this->idEmpresa]
        ];

        if ($this->ormEntityExiste) {
            $where = [
                [
                    'OR',
                    ['email_trabalho', $this->email_pessoal->email()],
                    ['email_pessoal', $this->email_pessoal->email()]
                ],
                ['empresa', $this->id_admin_empresa],
                ['id', '!=', $this->prop('id')]
            ];
        }

        if ($this->existe($where)) {
            mensagemErro('E-mail já existe!', 'O E-mail pessoal informado já está em uso por outro usuário.');
        }
    }

    /**
     * Valida se matrícula Já existe
     */
    protected function matriculaExiste()
    {
        if (!$this->propriedadeExiste('matricula') || empty($this->matricula)) {
            return;
        }
        $matricula = $this->matricula;
        $where = [
            ['matricula', $matricula],
            ['empresa', $this->idEmpresa]
        ];

        if ($this->ormEntityExiste) {
            $where = [
                ['matricula', $matricula],
                ['empresa', $this->id_admin_empresa],
                ['id', '!=', $this->prop('id')]
            ];
        }

        if ($this->existe($where)) {
            mensagemErro('Matrícula já existe!', 'A matrícula informada já está em uso por outro usuário.');
        }
    }

    /**
     * Valida se SIAPE Já existe
     */
    protected function siapeExiste()
    {
        if (!$this->propriedadeExiste('siape') || empty($this->siape)) {
            return;
        }
        $siape = $this->siape;
        $where = [
            ['siape', $siape],
            ['empresa', $this->idEmpresa]
        ];

        if ($this->ormEntityExiste) {
            $where = [
                ['siape', $siape],
                ['empresa', $this->id_admin_empresa],
                ['id', '!=', $this->prop('id')]
            ];
        }

        if ($this->existe($where)) {
            mensagemErro('SIAPE já existe!', 'O SIAPE informado já está em uso por outro usuário.');
        }
    }
}
