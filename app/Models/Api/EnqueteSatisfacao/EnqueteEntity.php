<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use ORM\Entity;

class EnqueteEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Navegar $navegar;
    public Procura $procura;
    public Suporte $suporte;
    public Atendimento $atendimento;
    public array $sistemas_clube;
    public string $comentario;
    public Status $status;
    protected string $ormTabela = TABELA_ENQUETE_SATISFACAO;
    protected array $ormBuscar = [
        'navegar', 'procura', 'suporte', 'atendimento',
        'comentario', 'sistemas_clube', 'status', 'data_criacao'
    ];
    protected array $ormInsert = [
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
    protected ?int $idUsuario;

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->setarIdUsuario();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->status = new Status(Status::NOVO);
    }
}
