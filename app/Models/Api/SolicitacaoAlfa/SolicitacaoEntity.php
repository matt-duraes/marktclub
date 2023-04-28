<?php

namespace App\Models\Api\SolicitacaoAlfa;

use App\Classes\SolicitacaoAlfa\Tipo;
use App\Classes\StatusGeral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Data;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_ALFA;
    protected array $ormBuscar = [
        'valor_emprestimo', 'quantidade_parcelas_restantes', 'taxa', 'nome',
        'orgao', 'cidade', 'email', 'telefone_fixo', 'telefone_celular',
        'observacao', 'data_simulacao', 'data_autorizacao', 'status', 'tipo'
    ];
    protected array $ormInsert = [
        'valor_emprestimo', 'quantidade_parcelas_restantes', 'taxa', 'nome',
        'orgao', 'cidade', 'email', 'telefone_fixo', 'telefone_celular',
        'observacao', 'data_simulacao', 'data_autorizacao', 'status', 'tipo'
    ];
    protected array $ormSalvar = [
        'valor_emprestimo', 'quantidade_parcelas_restantes', 'taxa', 'nome',
        'orgao', 'cidade', 'email', 'telefone_fixo', 'telefone_celular',
        'observacao'
    ];
    protected float $valor_emprestimo;
    protected int $quantidade_parcelas_restantes;
    protected float $taxa;
    protected Nome $nome;
    protected string $orgao;
    protected string $cidade;
    protected Email $email;
    protected Telefone $telefone_fixo;
    protected Telefone $telefone_celular;
    protected string $observacao;
    protected Data $data_simulacao;
    protected Data $data_autorizacao;
    protected Status $status;
    protected Tipo $tipo_solicitacao;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
