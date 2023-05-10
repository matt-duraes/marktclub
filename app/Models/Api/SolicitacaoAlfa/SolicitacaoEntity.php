<?php

namespace App\Models\Api\SolicitacaoAlfa;

use App\Classes\SolicitacaoAlfa\Tipo;
use App\Classes\StatusGeral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Cpf;
use Modules\Dinheiro;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $codigo_solicitacao;
    public Dinheiro $valor_emprestimo;
    public int $prazo;
    public Dinheiro $valor_parcela_atual;
    public int $quantidade_parcelas_restantes;
    public float $taxa;
    public Nome $nome;
    public Cpf $documento_cpf;
    public Email $email;
    public Telefone $telefone_celular;
    public Telefone $telefone_fixo;
    public string $cidade;
    public string $orgao;
    public string $observacao;
    public Status $status;
    public Tipo $tipo;
    protected string $ormTabela = TABELA_SOLICITACAO_ALFA;
    protected array $ormBuscar = [
        'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
        'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
        'telefone_celular', 'telefone_fixo', 'cidade', 'orgao', 'observacao', 'status', 'tipo'
    ];
    protected array $ormInsert = [
        'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
        'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
        'telefone_celular', 'telefone_fixo', 'cidade', 'orgao', 'observacao', 'status', 'tipo'
    ];
    protected array $ormSalvar = [
        'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
        'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
        'telefone_celular', 'telefone_fixo', 'cidade', 'orgao', 'observacao'
    ];
    protected string $ormValidarInsert = '
        valor_emprestimo|Valor desejado|vazio
        prazo|Prazo desejado|vazio
        valor_parcela_atual|Valor parcela atual|vazio
        quantidade_parcelas_restantes|Parcelas restantes|vazio
        taxa|Taxa|vazio
        nome|Nome|obrigatorio|valido
        documento_cpf|CPF|obrigatorio|valido
        email|E-mail|obrigatorio|valido
        telefone_celular|Telefone celular|obrigatorio|valido
        telefone_fixo|Telefone fixo|vazio
        cidade|Cidade|vazio
        orgao|Orgão|obrigatorio
        observacao|Observação|obrigatorio
        tipo|Tipo|obrigatorio|valido
    ';

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
