<?php

namespace App\Models\Api\UsuarioCliente\Validar;

use ORM\ORM;
use Modules\Cpf;
use App\Models\Api\ComercialEmpresa\HelperModel;

final class ValidarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public int $status = 404;
    public array $retorno = [
        'status' => 'erro',
        'erro'   => [
            'empresa'   => '',
            'cpf'       => '',
            'status'    => 'RECUSADO'
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
            ->campo(['id', 'id_admin_empresa', 'nome'])
            ->where([
                ['cpf', $this->cpf->numero()],
                ['status', 'in', [1, 2]]
            ])
            ->order('data_acesso', 'DESC')
            ->primeiro(retorno: self::RETORNO_ARRAY);
    }

    private function setarRetorno()
    {
        $usuario = $this->usuario;
        if (empty($usuario) || !array_key_exists('id', $usuario)) {
            $this->retorno['erro']['cpf'] = $this->cpf->numero();
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
                'empresa' => (new HelperModel(true))->pegarCampoPor(
                    where: ['id', $this->usuario['id_admin_empresa']],
                    campo: 'uuid'
                ),
                'cpf'     => $this->cpf->numero(),
                'status'  => 'LIBERADO'
            ]
        ];
    }
}
