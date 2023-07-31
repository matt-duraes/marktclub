<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\SolicitacaoDeclaracao\Status;
use App\Classes\SolicitacaoDeclaracao\Tipo;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\ValidarHelper;
use Http\Request;
use ORM\Entity;

class DeclaracaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $vinculo;
    public Tipo $tipo;
    public Status $status;
    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario'       => '->idUsuario',
        'status'           => 1
    ];
    protected array $ormBuscar = [
        'tipo', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'vinculo', 'tipo', 'status'
    ];
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        if ($this->request === null) {
            return;
        }

        $LojaEntity = new LojaEntity();
        $LojaEntity->idSlug(
            $this->request->getPost('url'),
            mensagem: 'Parceiro não encontrado ou inexistente',
            titulo: 'Inconsistências encontradas'
        );

        $this->vinculo = $LojaEntity->id;
        $this->tipo = new Tipo($this->request->getPost('tipo'));

        (new ValidarHelper())
            ->valor($this->tipo, 'Tipo', 'O Tipo deve ser um valor válido')
            ->obrigatorio()
            ->vazio()
            ->valido();
    }
}
