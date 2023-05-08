<?php

namespace App\Models\Api\SolicitacaoAlfa;

use App\Classes\SolicitacaoAlfa\Tipo;
use App\Classes\StatusGeral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Cpf;
use Modules\DataHora;
use Modules\Dinheiro;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_ALFA;
    protected array $ormBuscar = [
        'valor_emprestimo', 'prazo', 'valor_parcela_atual', 'quantidade_parcelas_restantes',
        'taxa', 'nome', 'documento_cpf', 'email', 'telefone_celular', 'telefone_fixo', 'orgao',
        'observacao', 'data_simulacao', 'data_autorizacao', 'status', 'tipo'
    ];
    protected array $ormInsert = [
        'valor_emprestimo', 'prazo', 'valor_parcela_atual', 'quantidade_parcelas_restantes',
        'taxa', 'nome', 'documento_cpf', 'email', 'telefone_celular', 'telefone_fixo', 'orgao',
        'observacao', 'data_simulacao', 'data_autorizacao', 'status', 'tipo'
    ];
    protected array $ormSalvar = [
        'valor_emprestimo', 'prazo', 'valor_parcela_atual', 'quantidade_parcelas_restantes',
        'taxa', 'nome', 'documento_cpf', 'email', 'telefone_celular', 'telefone_fixo', 'orgao',
        'observacao'
    ];
    protected string $ormValidarInsert = '
        valor_emprestimo|Valor desejado|vazio
        prazo|Prazo desejado|vazio
        valor_parcela_atual|Valor parcela atual|vazio
        quantidade_parcelas_restantes|Parcelas restantes|obrigatorio|vazio
        taxa|Taxa|vazio
        nome|Nome|obrigatorio|valido
        documento_cpf|CPF|obrigatorio|valido
        email|E-mail|obrigatorio|valido
        telefone_celular|Telefone celular|obrigatorio|valido
        telefone_fixo|Telefone fixo|vazio
        orgao|Orgão|obrigatorio
        observacao|Observação|obrigatorio
    ';
    protected Dinheiro $valor_emprestimo;
    protected int $prazo;
    protected Dinheiro $valor_parcela_atual;
    protected int $quantidade_parcelas_restantes;
    protected float $taxa;
    protected Nome $nome;
    protected Cpf $documento_cpf;
    protected Email $email;
    protected Telefone $telefone_celular;
    protected Telefone $telefone_fixo;
    protected string $orgao;
    protected string $observacao;
    protected DataHora $data_simulacao;
    protected DataHora $data_autorizacao;
    protected Status $status;
    protected Tipo $tipo_solicitacao;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
