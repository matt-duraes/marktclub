<?php

namespace App\Models\Api\SolicitacaoCredito;

use ORM\Entity;
use Erro\Excecao;
use Modules\Inteiro;
use Modules\Dinheiro;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Operadora;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\DadoBaseModel;

class CreditoEntity extends Entity
{
    use ValidarEmpresaTrait;
    use ValidarTrait;

    public Dinheiro $valor_parcela;
    public Operadora $operadora;
    public Tipo $tipo;
    public Dinheiro $valor_total;
    public Inteiro $parcela;
    public Status $status;
    public array $usuario;
    protected string $ormTabela = TABELA_SOLICITACAO_CREDITO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'id_usuario_cliente', 'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'data_criacao', 'data_atualizacao', 'status'
    ];
    protected array $ormSalvar = [
        'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'status'
    ];
    protected ?int $id_usuario_cliente;
    private int $idEmpresa;
    private ?int $idUsuario = null;
    protected string $validarSalvar = '
        operadora|Operadora|obrigatorio|vazio|valido
        tipo|Tipo de crédito|obrigatorio|vazio|valido
        valor_total|Valor total|obrigatorio|vazio|valido
        parcela|Quantidade de parcelas|obrigatorio|vazio|valido
        valor_parcela|Valor da Parcela|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->status = new Status(Status::NOVO);
        $Simulacao = new SimulacaoModel(
            operadora: $this->operadora,
            tipo: $this->tipo,
            valor_total: $this->valor_total,
            parcela: $this->parcela
        );
        $this->valor_parcela = $Simulacao->valorParcela;
    }

    protected function regraPosBuscar(): void
    {
        $this->buscarUsuario();
    }

    private function buscarUsuario(): void
    {
        $Usuario = new DadoBaseModel($this->id_usuario_cliente);
        if (!$Usuario->existe) {
            return;
        }
        $this->usuario = [
            'id'     => $Usuario->id,
            'nome'   => $Usuario->nome->nome(),
            'email'  => $Usuario->email->email(),
            'imagem' => $Usuario->imagem
        ];
    }
}
