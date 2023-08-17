<?php

namespace App\Models\Api\SolicitacaoCredito;

use ORM\Entity;
use Http\Request;
use Modules\Inteiro;
use Modules\Dinheiro;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Operadora;
use App\Models\Api\Trait\ValidarEmpresaTrait;

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
    protected string $ormTabela = TABELA_SOLICITACAO_CREDITO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'data_criacao', 'status'
    ];
    protected array $ormSalvar = [
        'operadora', 'tipo', 'valor_total', 'parcela',
        'valor_parcela', 'status'
    ];
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
     * @param Request|null $request
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraInsert()
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
}
