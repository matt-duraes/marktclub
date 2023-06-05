<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\SolicitacaoDeclaracao\Status;
use App\Classes\SolicitacaoDeclaracao\Tipo;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Http\Request;
use ORM\Entity;

class DeclaracaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $idEmpresa;
    public string $idUsuario;
    public string $vinculo;
    public Tipo $tipo;
    public Status $status;
    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'tipo', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'uuid' => 'cod',
        'vinculo', 'tipo', 'status'
    ];

    public function __construct(
        private readonly ?Request $request = null,
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    /**
     * @return void
     */
    public function regraInsert(): void
    {
        if ($this->request !== null) {
            $LojaEntity = new LojaEntity();
            $LojaEntity->idSlug(
                $this->request->url ?? '',
                mensagem: 'Parceiro não encontrado ou inexistente',
                titulo: 'Inconsistências encontradas'
            );

            $this->vinculo = $LojaEntity->id;
            $this->tipo = new Tipo($this->request->tipo ?? '');
            $this->status = new Status(Status::NOVA);
        }
    }
}
