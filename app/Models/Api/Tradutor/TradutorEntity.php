<?php

namespace App\Models\Api\Tradutor;

use App\Classes\Geral\Status;
use ORM\Entity;

class TradutorEntity extends Entity
{
    public string $termo;
    public array|string $traducao;
    public Status $status;
    protected string $ormTabela = TABELA_PAINEL_TRADUTOR;
    protected array $ormBuscar = [
        'termo', 'traducao', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'termo', 'traducao', 'status'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->traducao = jsonDecode($this->traducao, true, true);
    }

    protected function regraSalvar(): void
    {
        $traducoes['en'] = $this->traducao[0];
        $traducoes['es'] = $this->traducao[1];
        $this->traducao = jsonEncode($traducoes);
        $this->status = new Status(Status::ATIVO);
    }
}
