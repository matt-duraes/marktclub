<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\SolicitacaoDeclaracao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\DataHora;
use ORM\Entity;

class DeclaracaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;

    protected array $ormBuscar = [
        'id_usuario_cliente' => 'usuario',
        'tipo', 'data_criacao', 'data_atualizacao', 'data_validacao', 'status'
    ];

    protected string $usuario_cod;
    protected string $usuario_nome;
    protected string $usuario_cpf;
    protected string $usuario_estado_civil;
    protected string $usuario_documento_rg;
    protected DataHora $usuario_data_nascimento;
    protected string $usuario_cep;
    protected string $usuario_estado;
    protected string $usuario_cidade;
    protected string $usuario_bairro;
    protected int $usuario_numero;
    protected string $usuario_logradouro;
    protected string $usuario_complemento;
    protected string $dependente_cpf;
    protected string $dependente_nome;
    protected string $dependente_grau_parentesco;
    protected string $dependente_rg;
    protected DataHora $dependente_data_nascimento;
    protected int $usuario_status;
    protected Status $status;
    protected DataHora $data_validacao;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
