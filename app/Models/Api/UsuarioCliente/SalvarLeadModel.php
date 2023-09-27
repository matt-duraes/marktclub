<?php

namespace App\Models\Api\UsuarioCliente;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use ORM\ORM;

final class SalvarLeadModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa('empresa');
        parent::__construct();
    }

    /**
     * @param array $dado
     *
     * @return false|void
     * @throws Excecao
     */
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
            'cod'          => uuid(),
            'empresa'      => $this->idEmpresa,
            'titular'      => null,
            'tipo'         => 1,
            'status'       => 2
        ];

        $salvar = $this->dado($dado)->insert();
        if (existeErro($salvar, 'id')) {
            mensagemErro('Erro!', 'O Status foi alterado mas ouve um erro ao salvar usuário.');
        }
    }

    /**
     * @param $email
     *
     * @return bool
     * @throws Excecao
     */
    private function removerEmailJaExiste($email): bool
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
