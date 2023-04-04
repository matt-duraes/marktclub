<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class SalvarLeadModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private int $idEmpresa;
    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa('empresa');
    }

    public function salvarLead(array $dado)
    {
        $cpf = $dado['documento'];
        if (
            $this->existe([
            ['documento', $cpf],
            ['empresa', $this->idEmpresa]
            ])
        ) {
            return false;
        }

        if ($this->removerEmailJaExiste($dado['email_pessoal'])) {
            unset($dado['email_pessoal']);
        }
        if ($this->removerEmailJaExiste($dado['email_trabalho'])) {
            unset($dado['email_trabalho']);
        }
        if ($this->removerEmailJaExiste($dado['email_funcional'])) {
            unset($dado['email_funcional']);
        }

        $dado += [
            'usuario_lead' => 1,
            'cod' => uuid(),
            'empresa' => $this->idEmpresa,
            'titular' => null,
            'tipo' => 1,
            'status' => 2
        ];

        $salvar = $this->dado($dado)->insert();
        if (existeErro($salvar, 'id')) {
            mensagemErro('Erro!', 'O Status foi alterado mas ouve um erro ao salvar usuário.');
        }
    }
    private function removerEmailJaExiste($email)
    {
        return $this->existe([
            ['empresa', $this->idEmpresa],
            [
                'OR',
                ['email_pessoal', $email],
                ['email_trabalho', $email],
                ['email_funcional', $email],
            ]
        ]);
    }
}
