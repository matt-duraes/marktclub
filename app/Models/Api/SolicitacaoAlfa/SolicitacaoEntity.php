<?php

namespace App\Models\Api\SolicitacaoAlfa;

use App\Classes\SolicitacaoAlfa\Status;
use App\Classes\SolicitacaoAlfa\Tipo;
use App\Helpers\Alfa\AlfaCredito;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use Modules\Cpf;
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
        'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
        'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
        'telefone_celular', 'telefone_fixo', 'cidade', 'orgao', 'observacao', 'status', 'tipo'
    ];
    protected array $ormInsert = [
        'status', 'tipo'
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
    protected string $codigo_solicitacao;
    protected Nome $nome;
    protected Cpf $documento_cpf;
    protected Email $email;
    protected Telefone $telefone_celular;
    protected Telefone $telefone_fixo;
    protected Dinheiro $valor_emprestimo;
    protected int $prazo;
    protected Dinheiro $valor_parcela_atual;
    protected int $quantidade_parcelas_restantes;
    protected float $taxa;
    protected string $cidade;
    protected string $orgao;
    protected string $observacao;
    protected Tipo $tipo;
    protected Status $status;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->codigo_solicitacao = $this->gerarCodigoDaSolicitacao();
        if ((new SolicitacaoModel())->verificarExisteCodigo($this->codigo_solicitacao)) {
            $this->codigo_solicitacao = $this->gerarCodigoDaSolicitacao();
        }
        $this->status = new Status(Status::CRIADA);
    }

    /**
     * @return string Código aleátorio com base na data e hora atual
     */
    private function gerarCodigoDaSolicitacao(): string
    {
        return uniqid(date('YmdHi') . '-');
    }

    /**
     * @throws Excecao|Erro
     */
    public function regraPosInsert(): void
    {
        $this->status = new Status(Status::ENVIADA_ALFA);

        if (!(new AlfaCredito($this))->enviarSolicitacao()) {
            $this->status = new Status(Status::ERRO_ENVIAR);
        }
    }
}
