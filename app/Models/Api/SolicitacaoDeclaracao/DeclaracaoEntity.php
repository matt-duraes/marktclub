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

    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;
    protected array $ormInsert = [
        'id_empresa' => '->idEmpresa',
        'id_usuario' => '->idUsuario',
        'status'     => 1
    ];
    protected array $ormBuscar = [
        'tipo', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'uuid' => 'cod',
        'vinculo', 'tipo', 'status'
    ];
    protected string $idEmpresa;
    protected string $idUsuario;
    protected string $vinculo;
    protected Tipo $tipo;
    protected Status $status;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     */
    public function regraInsert(): void
    {
        if ($this->request === null) {
            return;
        }

        $LojaEntity = new LojaEntity();
        $LojaEntity->idSlug(
            $this->request->url,
            mensagem: 'Parceiro não encontrado ou inexistente',
            titulo: 'Inconsistências encontradas'
        );

        $this->vinculo = $LojaEntity->id;
        $this->tipo = new Tipo($this->request->tipo);
    }
}
