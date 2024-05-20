<?php

namespace App\Models\Api\UsuarioCliente\Validar;

use ORM\ORM;
use Modules\Cpf;

final class ValidarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public int $status = 404;
    public array $retorno = [
        'status' => 'erro',
        'erro'   => [
            'nome'   => '',
            'cpf'    => '',
            'status' => 'RECUSADO'
        ]
    ];
    private array $usuario = [];

    public function __construct(
        private Cpf $cpf
    ) {
        parent::__construct();

        $this->validarCpf();
        $this->buscarUsuario();
        $this->setarRetorno();
    }

    private function validarCpf()
    {
        if ($this->cpf->vazio()) {
            mensagemErroVazio('CPF');
        } elseif (!$this->cpf->valido()) {
            mensagemErroValido('CPF');
        }
    }

    private function buscarUsuario()
    {
        $this->usuario = $this
            ->campo(['id', 'nome'])
            ->where([
                ['cpf', $this->cpf->numero()],
                ['status', 'in', [1, 2]]
            ])
            ->primeiro(retorno: self::RETORNO_ARRAY);
    }

    private function setarRetorno()
    {
        $usuario = $this->usuario;
        if (empty($usuario) || !array_key_exists('id', $usuario)) {
            $this->retorno['erro']['cpf'] = $this->cpf->cpf();
            return;
        }
        $this->setarRetornoSucesso();
    }

    private function setarRetornoSucesso()
    {
        $this->status = 200;
        $this->retorno = [
            'status' => 'sucesso',
            'dado'   => [
                'nome'   => $this->usuario['nome'],
                'cpf'    => $this->cpf->cpf(),
                'status' => 'LIBERADO'
            ]
        ];
    }
}
