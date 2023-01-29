<?php

namespace App\Models\Api\Mensageria;

use ORM\Entity;

final class MensageriaEntity extends Entity
{
    protected string $_tabela = TABELA_SISTEMA_MENSAGERIA;

    protected array $_insert = [];
    protected array $_salvar = [];
    protected array $_buscar = [];

    private MensageriaInterface $Mensageria;
    public function __construct(
        public ?string $tipo = null,
        public ?array $payload = null
    ) {
        parent::__construct();

        if ($tipo == 'download.privado') {
            $this->Mensageria = new DownloadPrivadoHelper($payload);
        }
        mensagemErro('Erro!', 'Não foi passado um tipo válido.');
    }

    protected function insert()
    {
    }
}
