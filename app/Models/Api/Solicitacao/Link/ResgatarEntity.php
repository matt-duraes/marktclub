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
        private array $dado
    ) {
        parent::__construct();
        $this->validarDado();
        $this->buscarLink();
        $this->emitirLink();
    }

    private function validarDado()
    {
        if (!chaveExiste(['parceiro.id', 'usuario', 'empresa', 'data'], $this->dado)) {
            mensagemErro('Erro!', 'Não foi possível achar o código, por favor, tente novamente.');
        } elseif ($this->dado['data'] <= agora()) {
            pp($this->dado['data']);
            ppe(agora());
            mensagemErro('Vencido!', 'O link tem validade de 10 minutos, gere um novo link para continuar.');
        }
    }

    private function buscarLink()
    {
        $this->buscar([
            ['status', 1],
            ['id_admin_empresa', 'null'],
            ['id_usuario_cliente', 'null'],
            ['id_parceiro_loja', $this->dado['parceiro']['id']]
        ], mensagem: 'Os vouchers desse parceiro estão esgotados, estamos providenciando mais vouchers.');
    }

    private function emitirLink()
    {
        $this->id_admin_empresa = $this->dado['empresa'];
        $this->id_usuario_cliente = $this->dado['usuario'];
        $this->data_emissao = new DataHora(agora());
        $this->status = new Status(Status::EMITIDO);
        $this->salvar();
    }
}
