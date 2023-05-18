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
    protected array $ormBuscar = [
        'id_usuario_cliente' => 'usuario',
        'tipo', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'cod', 'empresa', 'usuario', 'vinculo', 'tipo', 'status'
    ];
    protected string $cod;
    protected string $empresa;
    protected string $usuario;
    protected string $vinculo;
    protected Tipo $tipo;
    protected Status $status;

    public function __construct(
        private readonly ?Request $request = null,
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function regraInsert(): void
    {
        $loja = (new LojaEntity())
            ->idSlug(
                $this->request->get('url', ''),
                mensagem: 'Parceiro não encontrado ou não existente'
            );

        $this->cod = uuid();
        $this->vinculo = $loja['id'];
        $this->tipo = new Tipo($this->request->get('tipo'));
        $this->status = new Status(Status::NOVA);
    }
}
