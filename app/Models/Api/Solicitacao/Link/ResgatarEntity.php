<?php

namespace App\Models\Api\Solicitacao\Link;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\Solicitacao\Link\Status;

final class ResgatarEntity extends Entity
{
    protected string $ormTabela = TABELA_SOLICITACAO_LINK;
    protected array $ormBuscar = [
        'link'
    ];
    protected array $ormUpdate = [
        'id_admin_empresa', 'id_usuario_cliente', 'data_emissao', 'status'
    ];
    public string $link;
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected DataHora $data_emissao;
    protected Status $status;

    public function __construct(
        private HashModel $Hash
    ) {
        parent::__construct();
        $this->buscarLink();
        $this->emitirLink();
    }

    private function buscarLink()
    {
        $this->buscar([
            ['status', 1],
            ['id_admin_empresa', 'null'],
            ['id_usuario_cliente', 'null'],
            ['id_parceiro_loja', $this->Hash->parceiro->id],
            ['data_vencimento', '>=', hoje()]
        ], mensagem: 'Os vouchers desse parceiro estão esgotados, estamos providenciando mais vouchers.');
    }

    private function emitirLink()
    {
        $this->id_admin_empresa = $this->Hash->empresa;
        $this->id_usuario_cliente = $this->Hash->usuario;
        $this->data_emissao = new DataHora(agora());
        $this->status = new Status(Status::EMITIDO);
        $this->salvar();
    }
}
