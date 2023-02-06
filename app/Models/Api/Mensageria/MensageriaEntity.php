<?php

namespace App\Models\Api\Mensageria;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\Mensageria\Status;

final class MensageriaEntity extends Entity
{
    protected string $_tabela = TABELA_SISTEMA_MENSAGERIA;

    protected array $_insert = ['tipo', 'payload', 'envio_uri', 'envio_metodo', 'envio_api', 'envio_scope', 'data_enviar_apos'];
    protected array $_update = ['data_envio', 'quantidade_envio', 'status_resposta'];
    protected array $_salvar = ['status'];
    protected array $_buscar = ['envio_uri', 'envio_api', 'envio_scope', 'data_enviar_apos'];

    private MensageriaInterface $Mensageria;
    public string $envio_uri;
    public string $envio_metodo;
    public Botao $envio_api;
    public string $envio_scope;
    public Status $status;
    protected DataHora $data_enviar_apos;

    /**
     * @param   null|string     $tipo       Tipo da mensagem
     * @param   null|array      $payload    Payload da mensagem
     * @param   null|DataHora   $dataEnvio  Caso a mensagem só possa ser enviada depois de uma hora específica
     */
    public function __construct(
        public ?string $tipo = null,
        public ?array $payload = null,
        public ?DataHora $dataEnvio = null
    ) {
        parent::__construct();

        if ($tipo == 'download.privado') {
            $this->Mensageria = new DownloadPrivadoHelper($payload);
            return;
        }
        mensagemErro('Erro!', 'Não foi passado um tipo válido.');
    }

    protected function regraInsert()
    {
        if ($this->dataEnvio instanceof DataHora && $this->dataEnvio->valido()) {
            $this->data_enviar_apos = $this->dataEnvio;
        }
        $this->envio_uri = $this->Mensageria->pegarLinkEnvio();
        $this->envio_api = $this->Mensageria->vaiUsarApi();
        $this->envio_scope = $this->Mensageria->pegarScopeEnvio();
        $this->envio_metodo = $this->Mensageria->pegarMetodoEnvio();
        $this->status = new Status(1);
    }
}
