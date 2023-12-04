<?php

namespace App\Models\Api\EnqueteSatisfacao;

use ORM\Entity;
use Erro\Excecao;
use Http\Request;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Suporte;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\EnqueteSatisfacao\Atendimento;

class EnqueteEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Navegar $navegar;
    public Procura $procura;
    public Suporte $suporte;
    public Atendimento $atendimento;
    public array|string $sistemas_clube;
    public string $comentario;
    public Status $status;
    protected string $ormTabela = TABELA_ENQUETE_SATISFACAO;
    protected array $ormBuscar = [
        'navegar', 'procura', 'suporte', 'atendimento',
        'comentario', 'sistemas_clube', 'status', 'data_criacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'navegar', 'procura', 'suporte', 'atendimento',
        'comentario', 'sistemas_clube', 'status'
    ];
    protected string $ormValidarSalvar = '
        navegar|Navegar|obrigatorio|vazio|valido
        procura|Procura|obrigatorio|vazio|valido
        suporte|Suporte|obrigatorio|vazio|valido
        atendimento|Atendimento|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    private ?int $idUsuario = null;

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->status = new Status(Status::NOVO);
    }

    public function regraPosBuscar(): void
    {
        $this->sistemas_clube = jsonDecode($this->sistemas_clube, true, true);
    }
}
