<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\DadoBaseModel;
use Erro\Excecao;
use Modules\Dinheiro;
use Modules\Inteiro;
use ORM\Entity;

class CreditoEntity extends Entity
{
    use ValidarTrait;
    use ValidarEmpresaTrait;

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
        'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'data_criacao', 'status', 'id_usuario_cliente'
    ];
    protected array $ormSalvar = [
        'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'status'
    ];
    protected ?int $id_usuario_cliente;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
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

    protected function regraPosBuscar()
    {
        $this->buscarUsuario();
    }

    private function buscarUsuario()
    {
        $Usuario = new DadoBaseModel($this->id_usuario_cliente);
        if (!$Usuario->existe) {
            return;
        }
        $this->usuario = [
            'id'     => $Usuario->id,
            'nome'   => $Usuario->nome->nome(),
            'email'  => $Usuario->email->email(),
            'imagem' => $Usuario->imagem,
        ];
    }
}
